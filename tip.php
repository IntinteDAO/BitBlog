<?php

include('config.php');
if(empty($_SESSION['login'])) { die('No auth'); }

if(!empty($_POST['id'])) {
    if (preg_match("/^[a-f0-9]{64}$/", $_POST['id'])) {
	if(file_exists('articles/'.$_POST['id'].'.json')) {
		$nickname = $_SESSION['login'];
		$post_id = $_POST['id'];
		$current_date = date("Y-m-d");

		if(file_exists("indexes/tips_self/$nickname/date/$current_date/$post_id")) {
			if(iterator_count(new FilesystemIterator(__DIR__.'/indexes/tips_self/'.$nickname.'/date/'.$current_date, FilesystemIterator::SKIP_DOTS)) >= $max_tips) { die('You have exceeded the maximum number of daily tips - wait until tomorrow!'); }
		}

		$article_author = json_decode(file('articles/'.$_POST['id'].'.json')[0], TRUE)['creator'];
		// No self-upvote
		if($article_author == $nickname) { die(); }

		// Random Token
		$token = rand(0, $tokens);

		// Author Reward
		if(!file_exists("indexes/tips/$article_author")) { mkdir("indexes/tips/$article_author"); }
		if(!file_exists("indexes/tips/$article_author/$post_id")) { mkdir("indexes/tips/$article_author/$post_id"); }
		touch("indexes/tips/$article_author/$post_id/$nickname");
		if(!file_exists("indexes/tips/$article_author/$post_id/tokens")) { mkdir("indexes/tips/$article_author/$post_id/tokens"); }
		if(!file_exists("indexes/tips/$article_author/$post_id/tokens/$token")) { mkdir("indexes/tips/$article_author/$post_id/tokens/$token"); }
		touch("indexes/tips/$article_author/$post_id/tokens/$token/$nickname");

		// Curator Reward
		if(!file_exists("indexes/tips_self/$nickname")) { mkdir("indexes/tips_self/$nickname"); }
		if(!file_exists("indexes/tips_self/$nickname/$token")) { mkdir("indexes/tips_self/$nickname/$token"); }
		if(!file_exists("indexes/tips_self/$nickname/all")) { mkdir("indexes/tips_self/$nickname/all"); }
		touch("indexes/tips_self/$nickname/$token/$post_id");
		touch("indexes/tips_self/$nickname/all/$post_id");
		if(!file_exists("indexes/tips_self/$nickname/date")) { mkdir("indexes/tips_self/$nickname/date"); }
		if(!file_exists("indexes/tips_self/$nickname/date/$current_date")) { mkdir("indexes/tips_self/$nickname/date/$current_date"); }
		touch("indexes/tips_self/$nickname/date/$current_date/$article_author");
	}
    }
}


?>