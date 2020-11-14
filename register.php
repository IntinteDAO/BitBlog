<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/encryption.php');

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
		$data['publickey'][0] = $keys['public'];
		$data['created'] = time();

		for($i=0; $i<=10; $i++) {
			$data['balance'][$i] = 0;
		}

		$recovery_phases_ids = array_rand($recovery_phases_database, $recovery_phases_quantity);

		for($i=0; $i<=$recovery_phases_quantity-1; $i++) {
			$recovery_phases = trim($recovery_phases.' '.$recovery_phases_database[$recovery_phases_ids[$i]]);
		}

		$data['recovery'] = password_hash($recovery_phases, PASSWORD_BCRYPT, ['cost' => 13]);
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
		echo 'The user is already created!';
		include('template/register.php');
	} else if($error == 2) {
		echo 'The user name The user name does not meet the criteria.';
		include('template/register.php');
	}


} else {
	include('template/register.php');
}

include('footer.php');

?>