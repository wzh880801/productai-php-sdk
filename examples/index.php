<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/config.inc.php';

$url = 'http://www.sinaimg.cn/dy/slidenews/24_img/2013_13/40223_662671_794351.jpg';

$product_ai = new \ProductAI\API(ACCESS_KEY_ID, SECRET_KEY);

$result = $product_ai->searchImage('classify_fashion', '_0000001', $url);
var_dump($result);

$result = $product_ai->searchImage('classify_sleeve', '_0000002', '@'.__DIR__.'/example.jpg');
var_dump($result);

// $result = $product_ai->searchImage('classify_color', '_0000003', '#test');
// var_dump($result);

$result = $product_ai->addImagesToSet(IMAGE_SET_ID, [
    [$url, 'test image 1'],
    [$url, 'test image 2'],
]);
var_dump($result);

$result = $product_ai->removeImagesFromSet(IMAGE_SET_ID, [
    [$url, 'test image 1'],
    [$url, 'test image 2'],
]);
var_dump($result);
