<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/date_distance.php');
include('template/article_comments.php');
include('functions/editor.php');
include('functions/verify_html.php');


if(!empty($_GET['id'])) {
	if (preg_match("/^[a-f0-9]{64}$/", $_GET['id'])) {
		if(file_exists('articles/'.$_GET['id'].'.json')) {
			$file = file('articles/'.$_GET['id'].'.json')[0];
			$json = json_decode($file, TRUE);
			$body = $json['body'];
			$nickname = 'fervi';
			$id_nickname = hash('sha256', $nickname);

			echo '<div class="col-12"><h2>'.htmlspecialchars($json['title']).'</h2>';
			echo 'by <a href="user.php?username='.$json['creator'].'">'.$json['creator'].'</a>';
			if($json['node'] != $node) { echo '@'.$json['node']; }
			echo ' | <a href="tags.php?tag='.$json['tags'][0].'">'.$json['tags'][0].'</a>';
			echo ' | '.time_elapsed_string('@'.$json['created']);
			echo '<hr></div>';
			echo '<div class="col-12">'.$body.'</div>';
			echo '<div class="col-12"><hr>';
			echo '<center>';
			if(!empty($nickname)) {
			if(file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname)) { $upvote_class = ''; } else { $upvote_class = 'class="d-none"';}
			if(file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname)) { $downvote_class = ''; } else { $downvote_class = 'class="d-none"';}
			if((!file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_nickname)) && (!file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_nickname))) { $upvote_class = ''; $downvote_class = ''; }
			echo '<a id="upvote" '.$upvote_class.' onclick="$.post(\'vote.php\', { upvote: 1, id: \''.$_GET['id'].'\'}); document.getElementById(\'downvote\').classList.remove(\'d-none\'); document.getElementById(\'upvote\').classList.add(\'d-none\');"><button type="button" class="btn btn-success"><i class="far fa-thumbs-up"></i> Like!</button></a> ';
			echo '<a id="downvote" '.$downvote_class.' onclick="$.post(\'vote.php\', { upvote: 0, id: \''.$_GET['id'].'\'}); document.getElementById(\'upvote\').classList.remove(\'d-none\'); document.getElementById(\'downvote\').classList.add(\'d-none\');"><button type="button" class="btn btn-danger"><i class="far fa-thumbs-down"></i> Dislike!</button></a> '; }
			if($tip_enable == 1) { echo '<a href="#"><button type="button" class="btn btn-warning"><i class="far fa-star"></i> Tip!</button></a>'; }
			echo '</center>';
			echo '<hr>';
			show_comments($_GET['id']);
			echo '<br>';
			echo add_comments($_GET['id']);
			echo '</div>';

		}
	}

}

include('footer.php');