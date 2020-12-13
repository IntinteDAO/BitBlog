<?php

function add_subcomment($id, $id_comment) {
	return 'document.getElementById("'.$id_comment.'").innerHTML = "'.addslashes(add_comments($id, $id_comment, 1)).'"';
}

function show_subcomments($id, $subid) {
$dirpath = getcwd().'/indexes/comments/'.$id.'/'.$subid.'/*.json';
$files = array();
$files = glob($dirpath);
usort($files, function($x, $y) {
    return filemtime($x) > filemtime($y);
});

if(!empty($files)) {
echo '<div class="vl"><p style="margin-left: 50px;">';
}

for($i=0; $i<=count($files)-1; $i++) {
	$read_comment = file($files[$i])[0];
	$read_comment_array = json_decode($read_comment, TRUE);
	echo '<img class="avatar" src="avatars/'.$read_comment_array['username'].'.webp"> <b>'.$read_comment_array['username'].'</b> - '.time_elapsed_string('@'.$read_comment_array['created']).'<br>';
	echo '<span class="text-break">'.htmlspecialchars(trim($read_comment_array['body'])).'</span>';
}

if(!empty($files)) {
	echo '</p></div>';
}

}

function show_comments($id) {
$dirpath = getcwd().'/indexes/comments/'.$id.'/*.json';
$files = array();
$files = glob($dirpath);

usort($files, function($x, $y) {
    return filemtime($x) < filemtime($y);
});

for($i=0; $i<=count($files)-1; $i++) {

	$read_comment = file($files[$i])[0];
	$read_comment_array = json_decode($read_comment, TRUE);
	$id_comment = str_replace('.json', '', str_replace(getcwd().'/indexes/comments/'.$id.'/', '', $files[$i]));

	if(isset($_SESSION['login'])) {
		echo '<img class="avatar" src="avatars/'.$read_comment_array['username'].'.webp"> <b>'.$read_comment_array['username'].'</b> - '.time_elapsed_string('@'.$read_comment_array['created']).' (<a class="link" onclick=\''.add_subcomment($id, $id_comment).';\'>Reply</a>)<br>';
	} else {
		echo '<img class="avatar" src="avatars/'.$read_comment_array['username'].'.webp"> <b>'.$read_comment_array['username'].'</b> - '.time_elapsed_string('@'.$read_comment_array['created']).'<br>';
	}

	echo '<p class="text-break">'.htmlspecialchars(trim($read_comment_array['body'])).'</p>';
	echo '<div id="'.$id_comment.'"></div>';

	if(file_exists(str_replace('.json', '', $files[$i]))) {
	echo show_subcomments($id, $id_comment);
	}

}





}

function add_comments($id, $subid = NULL, $nobr = NULL) {

if(empty($subid)) { $subid = ""; }

if(empty($nobr)) {
	return '
		<form method="POST" action="add_comment.php">
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="subid" value="'.$subid.'">
			<textarea class="form-control" minlength="10" maxlength="500" name="comment" placeholder="Paste your comment here"></textarea>
			<button class="btn btn-primary" type="submit">Add comment</button>
		</form>';
} else {
	return '<form method="POST" action="add_comment.php"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="subid" value="'.$subid.'"><textarea class="form-control" minlength="10" maxlength="500" name="comment" placeholder="Paste your comment here"></textarea><button class="btn btn-primary" type="submit">Add comment</button></form>';
}

}


?>