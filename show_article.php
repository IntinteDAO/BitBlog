<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/date_distance.php');
include('template/article_comments.php');
include('functions/editor.php');
include('functions/verify_html.php');

?>

<?php

if(!empty($_GET['id'])) {
	if (preg_match("/^[a-f0-9]{64}$/", $_GET['id'])) {
		if(file_exists('articles/'.$_GET['id'].'.json')) {
			$file = file('articles/'.$_GET['id'].'.json')[0];
			$json = json_decode($file, TRUE);
			$body = $json['body'];
			$nickname = $json['creator'];


			echo '<div class="col-12"><h2>'.htmlspecialchars($json['title']).'</h2>';
			echo 'by <a href="user.php?username='.$json['creator'].'">'.$json['creator'].'</a>';
			if($json['node'] != $node) { echo '@'.$json['node']; }
			echo ' | <a href="tags.php?tag='.$json['tags'][0].'">'.$json['tags'][0].'</a>';
			echo ' | '.time_elapsed_string('@'.$json['created']);
			echo '<hr></div>';

			if(in_array('nsfw', $json['tags'])) {
				echo '<div class="col-12">';
				echo '<a id="nsfw-info" class="btn btn-link" onclick="document.getElementById(\'nsfw\').removeAttribute(\'class\'); document.getElementById(\'nsfw-info\').remove();">NSFW Warning - This article may contain content you do not want to see in public (pornography, violent car accidents, etc.). If you want to see the content, press this warning.</a>';
				echo '<div id="nsfw" class="d-none">'.$body.'</div></div>';
			} else {
				echo '<div class="col-12">'.$body.'</div>';
			}

			echo '<div class="col-12"><hr>';
			if(!isset($_SESSION['login'])) { echo '<center>No account? Create one <a target="_blank" href="register.php">today</a>! For example, the account allows you to support authors of content</center>'; }
			echo '<center>';
			if(isset($_SESSION['login'])) {
				$id_username = hash('sha256', $_SESSION['login']);
				if(file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_username)) { $upvote_class = ''; } else { $upvote_class = 'class="d-none"';}
				if(file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_username)) { $downvote_class = ''; } else { $downvote_class = 'class="d-none"';}
				if((!file_exists('indexes/downvotes/'.$_GET['id'].'/'.$id_username)) && (!file_exists('indexes/upvotes/'.$_GET['id'].'/'.$id_username))) { $upvote_class = ''; $downvote_class = ''; }
				echo '<a id="upvote" '.$upvote_class.' onclick="$.post(\'vote.php\', { upvote: 1, id: \''.$_GET['id'].'\'}); document.getElementById(\'downvote\').classList.remove(\'d-none\'); document.getElementById(\'upvote\').classList.add(\'d-none\');"><button type="button" class="btn btn-success"><i class="far fa-thumbs-up"></i> Like!</button></a> ';
				echo '<a id="downvote" '.$downvote_class.' onclick="$.post(\'vote.php\', { upvote: 0, id: \''.$_GET['id'].'\'}); document.getElementById(\'upvote\').classList.remove(\'d-none\'); document.getElementById(\'downvote\').classList.add(\'d-none\');"><button type="button" class="btn btn-danger"><i class="far fa-thumbs-down"></i> Dislike!</button></a> ';
				if($tip_enable == 1) { echo '<a href="#"><button type="button" class="btn btn-warning"><i class="far fa-star"></i> Tip!</button></a>'; }
			}
			echo '</center>';
			echo '<hr>';
			show_comments($_GET['id']);
			echo '<br>';

			if(isset($_SESSION['login'])) {
				echo add_comments($_GET['id']);
			}

			echo '</div>';

		}
	}

}

include('footer.php');