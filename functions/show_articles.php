<?php

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
    $without_extension = str_replace('.json', '', str_replace(getcwd().'/'.$dir.'/', '', $files[$i]));
    echo '<div class="col-12"><a href="show_article.php?id='.$without_extension.'">'.str_replace(getcwd().'/'.$dir.'/', '', $files[$i]).'</a></div>';
}

}