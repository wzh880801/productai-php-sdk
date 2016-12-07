## ProductAI SDK for PHP

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
$result = $product_ai->searchImage($service_type, $service_id, $url);
```

#### Search image using file

```php
$result = $product_ai->searchImage($service_type, $service_id, '@'.$filename);
```

#### Search image using form

```php
$result = $product_ai->searchImage($service_type, $service_id, '#'.$form_name);
```

#### Upload image to image set

```php
$result = $product_ai->addImageToSet($set_id, $image_url, 'optional meta');
```

#### Upload images to image set using URL

```php
$result = $product_ai->addImagesToSet($set_id, [
    [$image_url_1, 'optional meta 1'],
    [$image_url_2, 'optional meta 2'],
]);
```

#### Upload images to image set using CSV file

```php
$result = $product_ai->addImagesToSet($set_id, $filename);
```

#### Remove images from image set using URL

```php
$result = $product_ai->removeImagesFromSet($set_id, [
    $image_url_1,
    $image_url_2,,
]);
```

#### Remove images from image set using CSV file

```php
$result = $product_ai->removeImagesFromSet($set_id, $filename);
```
