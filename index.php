<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/show_articles.php');

echo show_articles('articles');

include('footer.php');

?>