<?php

$website_title = "Intinte BitBlog";
$salt = '';
$iv = ''; // AES 256 IV required and stored by base64 [ base64_encode(openssl_random_pseudo_bytes(16)); ]
$node = 'bitblog.intinte.org'; // Node ID, recommended to set domain
$recovery_phases_quantity = 12;

$tip_enable = 1;

session_start();
?>