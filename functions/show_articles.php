<?php

include('functions/date_distance.php');
include('libs/other/parsedown/Parsedown.php');
include('functions/remove_markdown_chars.php');

function show_articles($dir)
{

if(isset($_SESSION['nsfw'])) {
	$nsfw = $_SESSION['nsfw'];
} else {
	$nsfw = 1;
}

$dirpath = getcwd().'/'.$dir.'/*';
$files = array();
$files = glob($dirpath);

usort($files, function($x, $y) {
    return filemtime($x) < filemtime($y);
});

echo '<script src="libs/js/bitblog/show_articles.js"></script>';
$min = min(count($files), 30) - 1;
for($i=0; $i<=$min; $i++) {



	$article_data = json_decode(file($files[$i])[0], TRUE);

	$is_nsfw = in_array("nsfw", $article_data['tags']);
	if($is_nsfw == 1 && $nsfw == 2) { continue; }

	$title = htmlspecialchars($article_data['title']);
	$article_id = str_replace('.json', '', str_replace(getcwd().'/'.$dir.'/', '', $files[$i]));
	$creator = $article_data['creator'];

	if(isset($_SESSION['login'])) {
		if(file_exists('indexes/mute/'.$_SESSION['login'].'/'.$creator)) {
			continue;
		}
	}

	global $node;
	if($article_data['node'] != $node) { $creator_node = $article_data['node']; } else { $creator_node = ''; }
	$Parsedown = new Parsedown();
	
	if($is_nsfw == 1 && $nsfw == 1) {
		$body = 'This article is hidden with an established policy to display the NSFW tag.';
	} else {
		$body = remove_markdown_chars(strip_tags(htmlspecialchars_decode($article_data['body'])));
	}

	$date = time_elapsed_string('@'.$article_data['created']);
	$tag = $article_data['tags'][0];

	if(file_exists('indexes/upvotes/'.$article_id)) {
		$upvotes = iterator_count(new FilesystemIterator(getcwd().'/indexes/upvotes/'.$article_id.'/', FilesystemIterator::SKIP_DOTS));
	} else {
		$upvotes = 0;
	}

	if(file_exists('indexes/downvotes/'.$article_id)) {
		$downvotes = iterator_count(new FilesystemIterator(getcwd().'/indexes/downvotes/'.$article_id.'/', FilesystemIterator::SKIP_DOTS));
	} else {
		$downvotes = 0;
	}

	if(isset($_SESSION['login'])) {
		if($_SESSION['login']!=$creator) {
			if(!file_exists('indexes/mute/'.$_SESSION['login'].'/'.$creator)) {
				$mute = '<a class=\'btn btn-danger element '.$creator.'\'>Mute</a><br>';
			}
		} else {
			$mute = '';
		}
	} else {
		$mute = '';
	}

	echo '<div class="col-12">
		<img class="avatar" src="avatars/'.$creator.'.webp">
		<a tabindex="0" class="popover-dismiss" data-toggle="popover" data-trigger="focus" title="'.$creator.'" data-content="

			'.$mute.'
			<a target=\'_blank\' href=\'user.php?username='.$creator.'\'>Show user profile</a>
		
		" data-html="true">'.$creator.'</a>
		'.$creator_node.' in 
		<a target="_blank" href="tags.php?tag='.$tag.'">#'.$tag.'</a> 
		'.$date.'
		<h4><a href="show_article.php?id='.$article_id.'"><p class="text-break text-truncate">'.$title.'</p></a></h4>
		<p class="truncate text-break">'.strip_tags($Parsedown->text($body)).'</p>
		<i class="fas fa-thumbs-up"></i> '.$upvotes.'
		<i class="fas fa-thumbs-down"></i> '.$downvotes.'
		<hr>
	</div>';
}

}