<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/encryption.php');
include('libs/other/KittyHash/kittyhash.php');

if(!empty($_POST['login'])) {

$error = 0;

	if (preg_match("@^([a-z]){3,32}$@", strtolower($_POST['login']))) {
		$username = strtolower($_POST['login']);
		if(file_exists('accounts/'.$username.'.json')) { $error = 1; }
	} else {
		$error = 2;
	}

	if($error == 0) {
		$keys = generate_pair_keys();
		$recovery_phases_database = file('libs/data/english.txt');
		$data['username'] = $username;
		$data['node'] = $node;
		$data['publickey'][0] = $keys['public'];
		$data['created'] = time();
		$data['avatar'] = 'avatars/'.$username.'.webp';

		$recovery_phases_ids = array_rand($recovery_phases_database, $recovery_phases_quantity);
		$recovery_phases = '';

		for($i=0; $i<=$recovery_phases_quantity-1; $i++) {
			$recovery_phases = trim($recovery_phases.' '.$recovery_phases_database[$recovery_phases_ids[$i]]);
		}

		generate_avatar($username);
		$data['recovery'] = password_hash($recovery_phases, PASSWORD_BCRYPT, ['cost' => 13]);
		$json = json_encode($data);
		$privkey_grow = grow_key($keys['private'], 1);
		$data['sign_client'] = sign_message($privkey_grow, $json);
		$privkey_grow = grow_key($server_privkey, 1);
		$data['sign_server'] = sign_message($privkey_grow, $json);

		$json = json_encode($data);
		$fp = fopen('accounts/'.$username.'.json', 'w');
		fwrite($fp, $json);
		fclose($fp);

		echo '<div class="col-12">';
		echo '<h2>Your user has been successfully added! Hello '.$username.'!</h2><br>';

		echo '<b>Your password: </b><p class="text-break">'.$keys['private'].'</p><br>';
		echo '<b>SAVE THEM IN A SAFE PLACE</b><br><br>';
		echo '<b>Recovery phases:</b> '.$recovery_phases.'<br>';
		echo '<b>If you forget your password, you can use Recovery Phases to generate new ones!</b>';
		echo '</div>';
	} else if($error == 1) {
		echo '<div class="col-12">The user is already created!</div>';
		include('template/register.php');
	} else if($error == 2) {
		echo '<div class="col-12">The user name does not meet the criteria.</div>';
		include('template/register.php');
	}


} else {
	include('template/register.php');
}

include('footer.php');

?>