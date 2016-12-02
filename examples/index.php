<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/config.inc.php';

$url = 'http://image13.poco.cn/mypoco/myphoto/20130813/19/64862850201308131948272576802736565_000.jpg';

$product_ai = new \ProductAI\API(ACCESS_KEY_ID, SECRET_KEY);

$result = $product_ai->searchImage('classify_fashion', '_0000001', $url);
var_dump($result, $product_ai->curl_info);
exit;
$result = $product_ai->searchImage('classify_sleeve', '_0000002', '@'.__DIR__.'/example.jpg');
var_dump($result, $product_ai->curl_info);

$result = $product_ai->searchImage('classify_color', '_0000003', '#test');
var_dump($result, $product_ai->curl_info);

$result = $product_ai->addImageToSet(IMAGE_SET_ID, $url, 'test image');
var_dump($result, $product_ai->curl_info);
exit;
$result = $product_ai->removeImageFromSet(IMAGE_SET_ID, $url);
var_dump($result, $product_ai->curl_info);

$result = $product_ai->addImagesToSet(IMAGE_SET_ID, [$url, 'test image']);
var_dump($result, $product_ai->curl_info);

$result = $product_ai->removeImagesFromSet(IMAGE_SET_ID, [$url]);
var_dump($result, $product_ai->curl_info);
