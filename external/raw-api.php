<?php
define('INC', 1);
define('MAGENTO_URL', 'http://magento2.local/');

include_once '_authentication.php';

if (!defined('AUTH_TOKEN')) {
    die('<pre>There has been an authentication error.</pre>');
}
echo '<pre>Authentication Token: '.var_export(AUTH_TOKEN, true).'.</pre>';

$resource = curl_init(MAGENTO_URL . 'index.php/rest/V1/customers/1');
curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
curl_setopt($resource, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . json_decode(AUTH_TOKEN)
));

$result = curl_exec($resource);

echo '<pre>'.var_export(json_decode($result), true).'</pre>';

