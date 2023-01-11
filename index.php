<?php
$passstr='VERY SECRET STR';  
function my_encrypt($data, $passphrase) {
    $secret_key = hex2bin($passphrase);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted_64 = openssl_encrypt($data, 'aes-256-cbc', $secret_key, 0, $iv);
    $iv_64 = base64_encode($iv);
    $json = new stdClass();
    $json->iv = $iv_64;
    $json->data = $encrypted_64;
    return base64_encode(json_encode($json));
} 
function my_decrypt($data, $passphrase) {
    $secret_key = hex2bin($passphrase);
    $json = json_decode(base64_decode($data));
    $iv = base64_decode($json->{'iv'});
    $encrypted_64 = $json->{'data'};
    $data_encrypted = base64_decode($encrypted_64);
    $decrypted = openssl_decrypt($data_encrypted, 'aes-256-cbc', $secret_key, OPENSSL_RAW_DATA, $iv);
    return $decrypted;
}
// The date
date_default_timezone_set('UTC');
$plain_date = date('Y-m-d H:i:s');

// Prepare the timezones
$utc = new DateTimeZone('+0000');
$ph  = new DateTimeZone('+0800');

// Conversion procedure
$datetime = new DateTime( $plain_date, $utc ); // UTC timezone
#$datetime->setTimezone( $ph ); // Philippines timezone

$data1 = $datetime->format('Y:m:d:H:i:s');
$data2 = ";PROGRAM VERSION";

$data3 = $data1 . $data2;
$result = my_encrypt($data3, $passstr);
echo($result)
?>