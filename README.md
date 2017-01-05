## ProductAI SDK for PHP

[![Latest Stable Version](https://poser.pugx.org/malong/productai/v/stable)](https://packagist.org/packages/malong/productai)
[![License](https://img.shields.io/github/license/MalongTech/productai-php-sdk.svg)](https://github.com/MalongTech/productai-php-sdk/blob/master/LICENSE)
[![Travis CI Build Status](https://travis-ci.org/MalongTech/productai-php-sdk.svg?branch=master)](https://travis-ci.org/MalongTech/productai-php-sdk)
[![Code Coverage](https://codecov.io/gh/MalongTech/productai-php-sdk/branch/master/graph/badge.svg)](https://codecov.io/gh/MalongTech/productai-php-sdk)

### Install

```shell
composer require malong/productai
```

### Usage

#### Initialize

```php
use ProductAI;

$product_ai = new ProductAI\API($access_key_id, $secret_key);
```

#### Search image using URL

```php
$result = $product_ai->searchImage($service_type, $service_id, $url, $loc, $tags, $count, $threshold);
```

```$loc```: Optional, default is the entire image. An area of the image which you want to search. The format is ```[$x, $y, $w, $h]```.

```$tags```: Optional, default is ```[]```. The keywords which you want to search.

```$count```: Optional, default is 20. The number of results that between 0 and 100. Public services do NOT support this argument.

```$threshold```: Optional, default is 0. A threshold value that between 0 and 1. Do NOT Recommend to set this argument.

#### Search image using file

```php
$result = $product_ai->searchImage($service_type, $service_id, '@'.$filename, $loc, $tags, $count, $threshold);
```

#### Search image using form

```php
$result = $product_ai->searchImage($service_type, $service_id, '#'.$form_name, $loc, $tags, $count, $threshold);
```

#### Upload one image to image set

```php
$result = $product_ai->addImageToSet($set_id, $image_url, 'optional meta', [
    'optional tag 1',
    'optional tag 2',
]);
```

#### Upload images to image set using URLs

```php
$result = $product_ai->addImagesToSet($set_id, [
    [
        $image_url_1,
        'optional meta 1',
        [
            'optional tag 1',
            'optional tag 2',
        ],
    ],
    [
        $image_url_2,
        'optional meta 2',
        [
            'optional tag 3',
            'optional tag 4',
        ],
    ],
]);
```

#### Upload images to image set using a CSV file

```php
$result = $product_ai->addImagesToSet($set_id, $filename);
```

#### Remove images from image set using URLs

```php
$result = $product_ai->removeImagesFromSet($set_id, [
    $image_url_1,
    $image_url_2,
]);
```

#### Remove images from image set using a CSV file

```php
$result = $product_ai->removeImagesFromSet($set_id, $filename);
```
