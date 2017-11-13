<?php
if (!defined('INC')) {
    die('<pre>You cannot call this script directly.</pre>');
}
if (!defined('MAGENTO_URL')) {
    die('<pre>No magento store URL specified.</pre>');
}

$apiConsumer = array(
    'username' => 'API', // admin user with correct resources
    'password' => 'pa$$w0rD'
);
$postFields = json_encode($apiConsumer);

$resource = curl_init(MAGENTO_URL . 'index.php/rest/V1/integration/admin/token');
curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($resource, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
curl_setopt($resource, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($postFields)
));

$token = curl_exec($resource);

DEFINE('AUTH_TOKEN', $token);