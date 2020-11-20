<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/show_articles.php');

if(!empty($_GET['username'])) {

	if(preg_match("@^([a-z]){3,32}$@", $_GET['username'])) {
		if(!empty('indexes/users/'.$_GET['username'])) {
			echo show_articles('indexes/users/'.$_GET['username']);
		}
	}
}

include('footer.php');

?>