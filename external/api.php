<?php
define('INC', 1);
define('MAGENTO_URL', 'http://magento2.local/');

include_once '_authentication.php';

if (!defined('AUTH_TOKEN')) {
    die('<pre>There has been an authentication error.</pre>');
}

$resource = curl_init(MAGENTO_URL . 'index.php/rest/V1/products?searchCriteria[pageSize]=5');
curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
curl_setopt($resource, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . json_decode(AUTH_TOKEN)
));

$result = curl_exec($resource);

$array = json_decode($result);
$products = $array->items;
function getProductPrice($product) {
    return property_exists($product, 'price') ? $product->price : false;
}
function getProductSku($product) {
    return property_exists($product, 'sku') ? $product->sku : false;
}
function getProductName($product) {
    return property_exists($product, 'name') ? $product->name : false;
}
function getProductDescription($product) {
    if (!property_exists($product, 'custom_attributes')) {
        return false;
    }
    $attributes = $product->custom_attributes;
    foreach ($attributes as $attribute) {
        if ($attribute->attribute_code == 'description') {
            return $attribute->value;
        }
    }
    return false;
}
function getProductImageUrl($product) {
    if (!property_exists($product, 'custom_attributes')) {
        return false;
    }
    $attributes = $product->custom_attributes;
    foreach ($attributes as $attribute) {
        if ($attribute->attribute_code == 'image') {
            return $attribute->value;
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Productos por API</title>
</head>
<body>
<h1>Algunos productos de nuestro catálogo</h1>
<?php foreach ($products as $product): ?>
    <?php if ($productName = getProductName($product)): ?>
        <h2><?php echo $productName; ?></h2>
        <?php if ($imageUrl = getProductImageUrl($product)): ?>
            <img src="<?php echo MAGENTO_URL . 'media/catalog/product' . $imageUrl; ?>" alt="<?php echo getProductSku($product); ?>"/>
        <?php else: ?>
            <pre><?php echo getProductSku($product); ?></pre>
        <?php endif; ?>
        <?php echo getProductDescription($product); ?>
    <?php endif; ?>
<?php endforeach; ?>
</body>
</html>
