<?php

function encrypt($plainText, $key, $iv, $cipher = "aes-256-ctr") {

        $iv = base64_decode($iv);
        $ciphertext = openssl_encrypt($plainText, $cipher, $key, $options=0, $iv);
        return trim($ciphertext);

}

function decrypt($encryptedText, $key, $iv, $cipher = "aes-256-ctr") {

        $iv = base64_decode($iv);
        $original_plaintext = openssl_decrypt($encryptedText, $cipher, $key, $options=0, $iv);
        return trim($original_plaintext);

}


function generate_pair_keys() {

$new_key_pair = openssl_pkey_new(array(
    "curve_name" => 'secp256k1',
    "private_key_type" => OPENSSL_KEYTYPE_EC,
));

openssl_pkey_export($new_key_pair, $private_key_pem);

$details = openssl_pkey_get_details($new_key_pair);
$public_key_pem = $details['key'];

$private_key_pem = str_replace('-----BEGIN EC PRIVATE KEY-----', '', $private_key_pem);
$private_key_pem = str_replace('-----END EC PRIVATE KEY-----', '', $private_key_pem);
$private_key_pem = str_replace(PHP_EOL, '', $private_key_pem);

$public_key_pem = str_replace('-----BEGIN PUBLIC KEY-----', '', $public_key_pem);
$public_key_pem = str_replace('-----END PUBLIC KEY-----', '', $public_key_pem);
$public_key_pem = str_replace(PHP_EOL, '', $public_key_pem);

return array(
		"private" => $private_key_pem,
		"public"=>$public_key_pem
	);

}

function grow_key($key, $type = NULL) {

if(empty($type)) {
	$return = '-----BEGIN PUBLIC KEY-----'.PHP_EOL;
} else {
	$return = '-----BEGIN EC PRIVATE KEY-----'.PHP_EOL;
}

$return = trim($return.(chunk_split($key, 64))).PHP_EOL;

if(empty($type)) {
	$return = $return.'-----END PUBLIC KEY-----';
} else {
	$return = $return.'-----END EC PRIVATE KEY-----';
}

return $return;
}

function sign_message($privatekey, $text) {
	openssl_sign($text, $signature, $privatekey);
	return base64_encode($signature);
}

function verify_message($publickey, $text, $signature) {
	return openssl_verify($text, $signature, $publickey);
}

?>
