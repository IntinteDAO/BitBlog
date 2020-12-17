<?php

$website_title = "Intinte BitBlog";
$salt = '';
$iv = ''; // AES 256 IV required and stored by base64 [ base64_encode(openssl_random_pseudo_bytes(16)); ]
$node = 'bitblog.intinte.org'; // Node ID, recommended to set domain
$recovery_phases_quantity = 12;
$max_articles_per_day = 5; //maximum number of articles added by the user per day
$max_comments_per_day = 30; //maximum number of comments added by the user per day
$server_privkey = '';
$tip_enable = 1;
$max_tips = 5;
$tokens = 0;

$recaptcha_keys['private'] = '';
$recaptcha_keys['public'] = '';

// Token data
$token[0]['name'] = "L-SAT";
$token[0]['description'] = "L-SAT is the smallest monetary unit of Bitcoin (Satoshi) in the second layer of Bitcoin - Liquid Network. Liquid Network is a network created for fast Bitcoin transfer between exchanges, but it is also perfect for creating value tokens (e.g. stablecoins).";
$token[0]['writer'] = 25;
$token[0]['user'] = 10;

// Bootstrap
    header('X-Frame-Options: SAMEORIGIN');


    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $secure = true;
    } else {
        $secure = false;
    }

    $httponly = true;
    $samesite = 'strict';
    $maxlifetime = 60 * 60 * 24 * 30;

if(php_sapi_name()!="cli") {
    if(PHP_VERSION_ID < 70300) {
        session_set_cookie_params($maxlifetime, '/; samesite='.$samesite, $_SERVER['HTTP_HOST'], $secure, $httponly);
    } else {
        session_set_cookie_params([
            'lifetime' => $maxlifetime,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ]);
    }
}

    session_start();
?>