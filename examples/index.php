<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/config.inc.php';

$product_ai = new \ProductAI\API(ACCESS_KEY_ID, SECRET_KEY);

$url = 'http://image13.poco.cn/mypoco/myphoto/20130813/19/64862850201308131948272576802736565_000.jpg';

$result = $product_ai->searchImage('detect_cloth', '_0000025', $url);
var_dump($result, $product_ai->curl_info);

$result = $product_ai->searchImage('detect_cloth', '_0000025', '@'.__DIR__.'/example.jpg');
var_dump($result, $product_ai->curl_info);

$result = $product_ai->searchImage('detect_cloth', '_0000025', '#test');
var_dump($result, $product_ai->curl_info);

$result = $product_ai->addImageToSet('12345', $url, 'test image');
var_dump($result, $product_ai->curl_info);

$result = $product_ai->removeImageFromSet('12345', $url);
var_dump($result, $product_ai->curl_info);

$result = $product_ai->addImageToSet('12345', [$url, 'test image']);
var_dump($result, $product_ai->curl_info);

$result = $product_ai->removeImageFromSet('12345', [$url]);
var_dump($result, $product_ai->curl_info);
