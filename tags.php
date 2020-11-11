<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/show_articles.php');
include('functions/tag_verify.php');


if(!empty($_GET['tag'])) {

	if(tag_verify($_GET['tag']) == 1) {
		if(!empty('indexes/tags/'.$_GET['tag'])) {
			echo show_articles('indexes/tags/'.$_GET['tag']);
		}
	}
}

include('footer.php');

?>