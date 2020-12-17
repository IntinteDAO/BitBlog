<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('template/profile.php');
include('functions/encryption.php');

if(isset($_SESSION['login'])) {

	$user_data = json_decode(file('accounts/'.$_SESSION['login'].'.json')[0], TRUE);
	unset($user_data['sign_client']);
	unset($user_data['sign_server']);

	$modification = 0;

	if(isset($_POST['nsfw'])) {
		if(($_POST['nsfw'] == 0 || $_POST['nsfw'] == 1 || $_POST['nsfw'] == 2) && ($_SESSION['nsfw'] != $_POST['nsfw']))  {
			$_SESSION['nsfw'] = $_POST['nsfw'];
			$user_data['nsfw'] = $_POST['nsfw'];
			$modification = 1;
		}
	}

	if(isset($_POST['tip'])) {
		if(($_POST['tip'] == 0 || $_POST['tip'] == 1) && ($_SESSION['tip'] != $_POST['tip']))  {
			$_SESSION['tip'] = $_POST['tip'];
			$user_data['tip'] = $_POST['tip'];
			$modification = 1;
		}
	}

	if($modification == 1) {
		$json = json_encode($user_data);
		$privkey_grow = grow_key(decrypt($_SESSION['privkey'], $salt, $iv), 1);
		$user_data['sign_client'] = sign_message($privkey_grow, $json);
		$privkey_grow = grow_key($server_privkey, 1);
		$user_data['sign_server'] = sign_message($privkey_grow, $json);
		$json = json_encode($user_data);
		file_put_contents('accounts/'.$_SESSION['login'].'.json',$json);
		echo 'The profile changes have been saved!';
	}

	echo show_profile_configuration();
} else {
	echo '<div class="col-12">You are not logged</div>';
}

include('footer.php');

?>
