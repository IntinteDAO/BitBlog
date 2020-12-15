<?php

include('config.php');
include('header.php');
include('template/navbar.php');
include('functions/date_distance.php');
include('template/article_comments.php');
include('functions/viewer.php');
include('functions/encryption.php');

?>

<?php

if(!empty($_GET['id'])) {
	if (preg_match("/^[a-f0-9]{64}$/", $_GET['id'])) {
		if(file_exists('articles/'.$_GET['id'].'.json')) {
			$file = file('articles/'.$_GET['id'].'.json')[0];
			$json = json_decode($file, TRUE);
			$body = str_replace("\n", "<br>", str_replace("\r\n", "<br>", trim($json['body'])));
			echo '<script>var content = "'.$body.'".split("<br>");</script>';
			$nickname = $json['creator'];

			// Verify article
			$article = $json;
			unset($article['sign_client']);
			unset($article['sign_server']);
			$verify_article = json_encode($article);
			if(!empty($json['sign_client'])) {
				$signed_article = base64_decode($json['sign_client']);
			}

			$publickeys = json_decode(file('accounts/'.$nickname.'.json')[0], TRUE)['publickey'];
			for($i=0; $i<=count($publickeys)-1; $i++) {
				$public_key = grow_key($publickeys[$i]);
				error_reporting(0);
				if(verify_message($public_key, $verify_article, $signed_article) == 1) { $verified=1; break; }
				error_reporting($php_errors);
			}

			if(empty($verified)) {
				echo '<div class="col-12 alert alert-danger" role="alert"><b>WARNING! Verification of this article has failed. This probably means that it was modified by someone other than the author!</b></div>';
			}

			echo '<div class="col-12 text-break"><h2>'.htmlspecialchars($json['title']).'</h2>';
			echo 'by <a href="user.php?username='.$json['creator'].'">'.$json['creator'].'</a>';
			if($json['node'] != $node) { echo '@'.$json['node']; }
			echo ' | <a href="tags.php?tag='.$json['tags'][0].'">'.$json['tags'][0].'</a>';
			echo ' | '.time_elapsed_string('@'.$json['created']);
			echo '<hr></div>';

			if(isset($_SESSION['nsfw'])) {
				$nsfw = $_SESSION['nsfw'];
			} else {
				 $nsfw = 1; }

			if(in_array('nsfw', $json['tags']) && $nsfw!=0) {
				echo '<div class="col-12 text-break">';
				echo '<a id="nsfw-info" class="btn btn-link" onclick="document.getElementById(\'nsfw\').removeAttribute(\'class\'); document.getElementById(\'nsfw-info\').remove();">NSFW Warning - This article may contain content you do not want to see in public (pornography, violent car accidents, etc.). If you want to see the content, press this warning.</a>';
				echo '<div id="nsfw" class="d-none"><div id="viewer"></div></div></div>';
				echo viewer();
			} else {
				echo '<div class="col-12 text-break"><div id="viewer"></div></div>';
				echo viewer();
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

				if($tip_enable == 1) {
					if($_SESSION['login'] != $nickname) {

							if(!file_exists('indexes/tips/'.$nickname.'/'.$_GET['id'].'/'.$_SESSION['login'])) {

								$current_date = date("Y-m-d");
								if(!file_exists('indexes/tips_self/'.$_SESSION['login'].'/date/'.$current_date.'/'.$nickname)) {
									echo '<a id="tip" onclick="$.post(\'tip.php\', { id: \''.$_GET['id'].'\'}); document.getElementById(\'tip\').remove();"><button type="button" class="btn btn-warning"><i class="far fa-star"></i> Tip!</button></a>';
								}
							}
					}
				}

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