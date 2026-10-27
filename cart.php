<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$data = Yaml::parseFile('private/products.yml');

// Bouw een array voor JavaScript: { id: { name, price } }
$jsProducts = [];
foreach ($data['products'] as $product) {
    $jsProducts[$product['product_id']] = [
        'name'  => $product['product_title'],
        'unit'  => $product['product_unit'],
        'price' => (float) str_replace(['€', ','], ['', '.'], $product['price']),
        'image'  => $product['image']
    ];
}

$mainLoader = new Mustache_Loader_FilesystemLoader(__DIR__ . '/views');
$partialsLoader = new Mustache_Loader_FilesystemLoader(__DIR__ . '/views/partials');

$m = new Mustache_Engine([
    'loader' => $mainLoader,
    'partials_loader' => $partialsLoader,
]);

echo $m->render('cart', [
    'title' => 'Jouw bestelling',
    'year' => date('Y'),
    'js_products_json' => json_encode($jsProducts),
]);