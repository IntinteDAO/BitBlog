<?php

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

?>
