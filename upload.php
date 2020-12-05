<?php

include('config.php');

if ($_FILES['file']['name']) {
 if (!$_FILES['file']['error']) {
    $name = hash("sha256", $salt.microtime(true));
    $ext = explode('.', $_FILES['file']['name']);
    $location = $_FILES["file"]["tmp_name"];
    if(@is_array(getimagesize($location))){

    if($_FILES['file']['type'] == "image/png") {
	$img = imagecreatefrompng($location);
    } else if($_FILES['file']['type'] == "image/jpeg") {
	$img = imagecreatefromjpeg($location);
    } else if($_FILES['file']['type'] == "image/gif") {
	// PHP GD don't support animated GIFS :(
	$img = imagecreatefromgif($location);
    }

    if(($_FILES['file']['type'] == "image/png") || ($_FILES['file']['type'] == "image/jpeg")) {
        $filename = $name . '.' . 'webp';
        $destination = 'images/' . $filename;
	imagepalettetotruecolor($img);
	imagealphablending($img, true);
	imagesavealpha($img, true);
	$file = imagewebp($img, $destination, 80);
    } else {
        $filename = $name . '.' . $ext[1];
        $destination = 'images/' . $filename;
	move_uploaded_file($location, $destination);
    }

    echo str_replace('upload.php', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]").'images/' . $filename;
    } else {
	    echo $message = 'Something wrong with this Image!';
    }
 }
 else
 {
  echo  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
 }
}
