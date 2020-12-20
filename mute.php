<?php

include('config.php');
print_r($_POST);

if(!empty($_SESSION['login'])) {
	if(!empty($_POST['username'])) {
		if(preg_match("@^([a-z]){3,32}$@", $_POST['username'])) {
			$ignore = $_POST['username'];
			$username = $_SESSION['login'];
			if($ignore == $username) { die(); }

			if(file_exists('accounts/'.$ignore.'.json')) {
				if(!file_exists('indexes/mute/'.$username)) {
					mkdir('indexes/mute/'.$username);
				}

				if(file_exists('indexes/mute/'.$username.'/'.$ignore)) {
					unlink('indexes/mute/'.$username.'/'.$ignore);
				} else {
					touch('indexes/mute/'.$username.'/'.$ignore);
				}

			}
		}
	}
}

?>