<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/show_articles.php');



if(!empty($_GET['tag'])) {

	if(!empty('indexes/tags/'.$_GET['tag'])) {
		echo show_articles('indexes/tags/'.$_GET['tag']);
	}

}

include('footer.php');

?>