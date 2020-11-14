<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/encryption.php');

if(isset($_SESSION['login'])) {
	die('You are already logged');
}
if((!empty($_POST['login'])) && (!empty($_POST['pkey']))) {

	$error = 0;

	if (preg_match("@^([a-z]){3,32}$@", strtolower($_POST['login']))) {
		$username = strtolower($_POST['login']);
		if(!file_exists('accounts/'.$username.'.json')) { $error = 1; }
	} else {
		$error = 2;
	}

	if($error == 0) {
		$php_errors = error_reporting();
		$private_key = grow_key($_POST['pkey'], 1);
		$user_data = json_decode(file('accounts/'.$username.'.json')[0], TRUE);
		error_reporting(0);
		$signature = base64_decode(sign_message($private_key, $username));
		error_reporting($php_errors);

		// Grow public keys and verify signature
		for($i=0; $i<=count($user_data['publickey'])-1; $i++) {
			$public_key = grow_key($user_data['publickey'][$i]);
			error_reporting(0);
			if(verify_message($public_key, $username, $signature) == 1) { $auth=1; break; }
			error_reporting($php_errors);
		}

		if(isset($auth)) {
			$_SESSION['login'] = $username;
			$_SESSION['privkey'] = encrypt($_POST['pkey'], $salt, $iv);
			echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		} else {
			echo 'Your private key is not correct';
		}

	} else {
	if($error == 1) { echo 'This user does not exists'; } else { echo 'The username does not meet our creteries'; }
	include('template/login.php');
}


} else {
	include('template/login.php');
}

include('footer.php');