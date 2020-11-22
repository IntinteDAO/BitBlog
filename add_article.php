<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/editor.php');
include('functions/verify_html.php');
include('functions/tag_verify.php');

$current_date = date("Y-m-d");
if(empty($_SESSION['login'])) { die('You are not logged in'); }
if(iterator_count(new FilesystemIterator(__DIR__.'/indexes/antispam_articles/'.$current_date.'/'.$_SESSION['login'], FilesystemIterator::SKIP_DOTS)) >= $max_articles_per_day) { die('You have exceeded the maximum number of daily entries - wait until tomorrow!'); }

if( (!empty($_POST['title'])) && (!empty(trim($_POST['text']))) && !empty(trim($_POST['tags'])) ) {

	$error = 0;

	if(strlen($_POST['text']) > 1000000) {
		echo ("Too much data. Delete a few pictures or move them to another hosting");
		$error = 1;
	}

	if($error == 0) {
		$save_to_file['creator'] = $_SESSION['login'];
		$save_to_file['node'] = $node;

		$verify_tags = explode(',', $_POST['tags']);
		$tags = [];

		for($i=0; $i<=count($verify_tags)-1; $i++) {
			if(tag_verify($verify_tags[$i])==1) {
				array_push($tags, $verify_tags[$i]);
			}
		}

		if(empty($tags)) { die(); }

		$save_to_file['created'] = time();
		$save_to_file['last_update'] = time();
		if(strlen($_POST['title']) > 120) { die('The content of the Title field is too long.'); }

		$save_to_file['title'] = $_POST['title'];
		$save_to_file['body'] = str_replace('</body></html>', '', str_replace('<html><body>', '', str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', verify_html($_POST['text']))));
		if(empty(trim(strip_tags($save_to_file['body'])))) { die(); }
		if(strlen($save_to_file['body']) > 16384) { die('The content of the Text field is too long.'); }
		$save_to_file['tags'] = $tags;
		$json = json_encode($save_to_file);
		$filename = hash("sha256", $json);
		$fp = fopen('articles/'.$filename.'.json', 'w');
		fwrite($fp, $json);
		fclose($fp);

		if(!file_exists('indexes/users/'.$save_to_file['creator'])) { mkdir('indexes/users/'.$save_to_file['creator']); }
		mkdir('indexes/comments/'.$filename);

		for($i=0; $i<=count($tags)-1; $i++) {
			if(!file_exists('indexes/tags/'.$tags[$i])) { mkdir('indexes/tags/'.$tags[$i]); }
			symlink('../../../articles/'.$filename.'.json', 'indexes/tags/'.$tags[$i].'/'.$filename.'.json');
		}


		$username = $_SESSION['login'];
		if(!file_exists('indexes/antispam_articles/'.$current_date)) { mkdir('indexes/antispam_articles/'.$current_date);}
		if(!file_exists('indexes/antispam_articles/'.$current_date.'/'.$username)) { mkdir('indexes/antispam_articles/'.$current_date.'/'.$username); }
		symlink('../../../../articles/'.$filename.'.json', 'indexes/antispam_articles/'.$current_date.'/'.$save_to_file['creator'].'/'.$filename.'.json');
		symlink('../../../articles/'.$filename.'.json', 'indexes/users/'.$save_to_file['creator'].'/'.$filename.'.json');
		echo '<meta http-equiv="refresh" content="0; url=show_article.php?id='.$filename.'" />';
	}

} else {
	
	echo '<div class="col-12"><form method="POST">
	<input id="Id" type="hidden" name="text" value="">
	<input class="form-control" type="text" name="title" placeholder="Title">';
	echo init_editor();
	echo '
	<input type="text" id="tags" name="tags" data-role="tagsinput" value="" placeholder="Tags">
	Tags - (like bitcoin, community, games) Use the prefix for language tags (pl-bitcoin, de-internet, fr-crypto etc.)<br>
	<button class="btn btn-primary" onclick="document.getElementById(\'Id\').value=$(\'#summernote\').summernote(\'code\');">Add comment</button>
	</form></div>';
}

include('footer.php');
?>