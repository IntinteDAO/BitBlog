<?php

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
	$cut = imagecreatetruecolor($src_w, $src_h);
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
	imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

function generate_avatar($name) {

	chdir('libs/other/KittyHash');
	$img_x = 300;
	$img_y = 300;
	$hash = crc32($name);
	srand($hash);

	// Background
	$rand_bg = rand(1, 20);
	$im = imagecreatefrompng("backgrounds/$rand_bg.png");
	$im = imagescale($im, 1024, 1024);

	// Accessory
	$accessory_rand = rand(0, 15);
	$im_accessory = imagecreatefrompng("sets/accessories/$accessory_rand.png");

	// Body
	$body_rand = rand(0, 14);
	$im_body = imagecreatefrompng("sets/body/$body_rand.png");

	// Eyes
	$eyes_rand = rand(0, 14);
	$im_eyes = imagecreatefrompng("sets/eyes/$eyes_rand.png");

	// Fur
	$fur_rand = rand(0, 9);
	$im_fur = imagecreatefrompng("sets/fur/$fur_rand.png");

	// Mouth
	$mouth_rand = rand(0, 9);
	$im_mouth = imagecreatefrompng("sets/mouth/$mouth_rand.png");

	// Merge all images
	imagecopymerge_alpha($im, $im_body, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_fur, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_accessory, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_mouth, 0, 0, 0, 0, 1024, 1024, 100);
	imagecopymerge_alpha($im, $im_eyes, 0, 0, 0, 0, 1024, 1024, 100);

	$im = imagescale($im, $img_x, $img_y);

	chdir('../../../');
	imagewebp($im, 'avatars/'.$name.'.webp');
	imagedestroy($im);

}