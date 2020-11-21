<?php

include('functions/date_distance.php');

function show_articles($dir)
{

$dirpath = getcwd().'/'.$dir.'/*';
$files = array();
$files = glob($dirpath);

usort($files, function($x, $y) {
    return filemtime($x) < filemtime($y);
});

$min = min(count($files), 30) - 1;
for($i=0; $i<=$min; $i++) {
    $article_data = json_decode(file($files[$i])[0], TRUE);
    $title = htmlspecialchars($article_data['title']);
    $article_id = str_replace('.json', '', str_replace(getcwd().'/'.$dir.'/', '', $files[$i]));
    $creator = $article_data['creator'];
    global $node;
    if($article_data['node'] != $node) { $creator_node = $article_data['node']; } else { $creator_node = ''; }
    $body = strip_tags($article_data['body']);
    $date = time_elapsed_string('@'.$article_data['created']);
    $tag = $article_data['tags'][0];

    echo '<div class="col-12">
	<img class="avatar" src="http://185.238.72.170/KittyHash/?name='.$creator.'">
	<a target="_blank" href="user.php?username='.$creator.'">'.$creator.'</a> 
	'.$creator_node.' in 
	<a target="_blank" href="tags.php?tag='.$tag.'">#'.$tag.'</a> 
	'.$date.'
	<h4><a href="show_article.php?id='.$article_id.'"><p class="text-break text-truncate">'.$title.'</p></a></h4>
	<p class="truncate">'.$body.'</p>
	<hr>
    </div>';
}

}
