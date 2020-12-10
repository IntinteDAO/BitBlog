<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/encryption.php');
include('functions/editor.php');
include('functions/verify_html.php');
include('functions/tag_verify.php');

$current_date = date("Y-m-d");
if(empty($_SESSION['login'])) { die('You are not logged in'); }
if(!file_exists('indexes/antispam_articles/'.$current_date)) { mkdir('indexes/antispam_articles/'.$current_date);}
if(!file_exists('indexes/antispam_articles/'.$current_date.'/'.$_SESSION['login'])) { mkdir('indexes/antispam_articles/'.$current_date.'/'.$_SESSION['login']); }
if(iterator_count(new FilesystemIterator(__DIR__.'/indexes/antispam_articles/'.$current_date.'/'.$_SESSION['login'], FilesystemIterator::SKIP_DOTS)) >= $max_articles_per_day) { die('You have exceeded the maximum number of daily entries - wait until tomorrow!'); }

if( (!empty($_POST['title'])) && (!empty(trim($_POST['text']))) && !empty(trim($_POST['tags'])) ) {
	$error = 0;

	if(strlen($_POST['title']) > 120) { $error = 2; echo 'The content of the Title field is too long.'; }
	if(strlen($_POST['text']) > 16384) { $error = 1; echo ("The content of the Text field is too long."); }

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

		$save_to_file['title'] = $_POST['title'];
		$save_to_file['body'] = htmlspecialchars($_POST['text']);

		if(empty(trim(strip_tags($save_to_file['body'])))) { die(); }
		$save_to_file['tags'] = $tags;
		$json = json_encode($save_to_file);

		$privkey_grow = grow_key(decrypt($_SESSION['privkey'], $salt, $iv), 1);
		$save_to_file['sign_client'] = sign_message($privkey_grow, $json);
		$privkey_grow = grow_key($server_privkey, 1);
		$save_to_file['sign_server'] = sign_message($privkey_grow, $json);

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

		symlink('../../../../articles/'.$filename.'.json', 'indexes/antispam_articles/'.$current_date.'/'.$save_to_file['creator'].'/'.$filename.'.json');
		symlink('../../../articles/'.$filename.'.json', 'indexes/users/'.$save_to_file['creator'].'/'.$filename.'.json');
		echo '<meta http-equiv="refresh" content="0; url=show_article.php?id='.$filename.'" />';
	}

} else {
	
	echo '<div class="col-12"><form method="POST">
	<input id="Id" type="hidden" name="text" value="">
	<input class="form-control" id="title" type="text" name="title" placeholder="Title">';
	echo init_editor();
	echo '
	<input type="text" id="tags" name="tags" data-role="tagsinput" value="" placeholder="Tags">
	Tags - (like bitcoin, community, games) Use the prefix for language tags (pl-bitcoin, de-internet, fr-crypto etc.)<br>
	<button class="btn btn-primary" id="submit" onclick="document.getElementById(\'Id\').value=editor.convertor.toMarkdown(editor.getMarkdown());">Add article</button>
	</form></div>';
}

include('footer.php');
?>

<script src="libs/js/bitblog/add_article.js"></script>