<?php
require 'vendor/autoload.php';

$mainLoader = new Mustache_Loader_FilesystemLoader(__DIR__ . '/views');
$partialsLoader = new Mustache_Loader_FilesystemLoader(__DIR__ . '/views/partials');

$m = new Mustache_Engine([
    'loader' => $mainLoader,
    'partials_loader' => $partialsLoader,
]);

echo $m->render('contact', [
    'year' => date('Y')
]);