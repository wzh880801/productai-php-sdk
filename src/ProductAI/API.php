<?php

namespace ProductAI;

use UnexpectedValueException;
use CURLFile;

class API extends Base
{
    public function __call($name, $arguments)
    {
        $this->initialize();
    }

    public function searchImage($service_type, $service_id, $image, $loc = [])
    {
        $prefix = substr($image, 0, 1);
        $image = substr($image, 1);

        switch ($prefix) {
            case '#':
            case '@':
                if ($prefix == '#') {
                    $image = $_FILES[$image]['tmp_name'];

                    if (!isset($image) || !is_uploaded_file($image)) {
                        throw new UnexpectedValueException("Possible file upload attack: $image");
                    }
                }

                $this->body['search'] = new CURLFile($image);

                break;

            default:
                $this->body['url'] = $image;

                break;
        }

        if ($loc) {
            $this->body['loc'] = implode('-', $loc);
        }

        return $this->curl($service_type, $service_id);
    }

    public function addImageToSet($set_id, $image_url, $meta = '')
    {
        $this->body['image_url'] = $image_url;

        if ($meta) {
            $this->body['meta'] = $meta;
        }

        return $this->curl('image_sets', "_0000014/$set_id");
    }

    public function removeImageFromSet($set_id, $image_url)
    {
        $this->method = 'DELETE';
        $this->body['image_url'] = $image_url;

        return $this->curl('image_sets', "_0000014/$set_id");
    }

    public function addImagesToSet($set_id, $images)
    {
        file_put_contents('php://temp', $this->convertArrayToCSV($images));

        $this->body['urls_to_add'] = new CURLFile('php://temp');

        return $this->curl('image_sets', "_0000014/$set_id");
    }

    public function removeImagesFromSet($set_id, $images)
    {
        file_put_contents('php://temp', $this->convertArrayToCSV($images));

        $this->body['urls_to_del'] = new CURLFile('php://temp');

        return $this->curl('image_sets', "_0000014/$set_id");
    }
}
