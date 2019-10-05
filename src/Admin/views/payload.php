<?php

use VE\Electro\Product\ProductRepository;
use VE\Electro\Electro;

$product = ProductRepository::get($post->ID);

echo '<pre>'. json_encode($product->payload(), JSON_PRETTY_PRINT) . "</pre>";
