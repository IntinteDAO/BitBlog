<?php

include('config.php');

$current_date = date("Y-m-d");
if(empty($_SESSION['login'])) { die('Need authorization'); }
if(!file_exists('indexes/antispam_comments/'.$current_date)) { mkdir('indexes/antispam_comments/'.$current_date);}
if(!file_exists('indexes/antispam_comments/'.$current_date.'/'.$_SESSION['login'])) { mkdir('indexes/antispam_comments/'.$current_date.'/'.$_SESSION['login']); }
if(iterator_count(new FilesystemIterator(__DIR__.'/indexes/antispam_comments/'.$current_date.'/'.$_SESSION['login'], FilesystemIterator::SKIP_DOTS)) >= $max_comments_per_day) { die('You have exceeded the maximum number of daily entries - wait until tomorrow!'); }

if( (!empty($_POST['id'])) && (!empty($_POST['comment'])) ) {
	if( (strlen(trim($_POST['comment'])) < 10) || (trim(strlen($_POST['comment'])) > 500) ) { die(); }
        if (preg_match("/^[a-f0-9]{64}$/", $_POST['id'])) {
                if(file_exists('articles/'.$_POST['id'].'.json')) {

			$id = $_POST['id'];

			// If comment directory NOT exists - create it
			if(!file_exists('indexes/comments/'.$id)) { mkdir('indexes/comments/'.$_POST['id']); }

			$data['username'] = $_SESSION['login'];
			$data['node'] = $node;
			$data['created'] = time();
			$data['body'] = trim($_POST['comment']);
			$json = json_encode($data);

			$filename = hash("sha256", $json);

			if(!empty($_POST['subid'])) {
				if (!preg_match("/^[a-f0-9]{64}$/", $_POST['subid'])) {
					die();
				}
				if(file_exists('indexes/comments/'.$id.'/'.$_POST['subid'].'.json')) {

				// If subcomment directory NOT exists - create it
				if(!file_exists('indexes/comments/'.$id.'/'.$_POST['subid'])) { mkdir('indexes/comments/'.$_POST['id'].'/'.$_POST['subid']); }

				$subid = $_POST['subid'];
				}
			}


	    		if(empty($_POST['subid'])) {
				$fp = fopen('indexes/comments/'.$id.'/'.$filename.'.json', 'w');
			} else {
				$fp = fopen('indexes/comments/'.$id.'/'.$subid.'/'.$filename.'.json', 'w');
			}

			touch('indexes/antispam_comments/'.$current_date.'/'.$_SESSION['login'].'/'.$filename);
			fwrite($fp, $json);
			fclose($fp);
			echo '<meta http-equiv="refresh" content="0; url=show_article.php?id='.$id.'" />';
		}
	}
}

?>