<?php

namespace ProductAI;

use BadMethodCallException;
use OutOfBoundsException;
use UnexpectedValueException;
use CURLFile;

class API extends Base
{
    public function __call($name, $args)
    {
        if (method_exists($this, $name)) {
            $this->initialize();

            return call_user_func_array([$this, $name], $args);
        } else {
            throw new BadMethodCallException('Call to undefined method '.get_class($this)."::{$name}()");
        }
    }

    protected function searchImage($service_type, $service_id, $image, $loc = [], $tags = [], $count = 20, $threshold = 0)
    {
        $prefix = substr($image, 0, 1);

        switch ($prefix) {
            case '#':
            case '@':
                $image = substr($image, 1);

                if ($prefix == '#') {
                    if (!isset($_FILES[$image])) {
                        throw new OutOfBoundsException("name $image not found in forms");
                    }

                    $image = $_FILES[$image]['tmp_name'];

                    if (!is_uploaded_file($image)) {
                        throw new UnexpectedValueException("possible file upload attack: $image");
                    }
                }

                $this->headers['x-ca-file-md5'] = md5_file($image);
                $this->body['search'] = new CURLFile($image);

                break;

            default:
                $this->body['url'] = $image;

                break;
        }

        if ($loc) {
            $this->body['loc'] = implode('-', $loc);
        }

        if ($tags) {
            $this->body['tags'] = implode('|', $loc);
        }

        if ($count) {
            $this->body['count'] = intval($count);
        }

        if ($threshold && is_numeric($threshold)) {
            $this->body['threshold'] = $threshold;
        }

        return $this->curl($service_type, $service_id);
    }

    /*
    protected function addImageSet($name, $description = '')
    {
        $this->body['name'] = $name;
        $this->body['description'] = $description;

        return $this->curl('image_sets', '_0000014');
    }

    protected function removeImageSet($set_id)
    {
        $this->method = 'DELETE';

        return $this->curl('image_sets', "_0000014/$set_id");
    }
    */

    protected function addImageToSet($set_id, $image_url, $meta = '', $tags = [])
    {
        $this->body['image_url'] = $image_url;

        if ($meta) {
            $this->body['meta'] = $meta;
        }

        if ($tags) {
            $this->body['tags'] = implode('|', $tags);
        }

        return $this->curl('image_sets', "_0000014/$set_id");
    }

    /*
    protected function removeImageFromSet($set_id, $image_url)
    {
        $this->method = 'DELETE';

        $this->body['image_url'] = $image_url;

        return $this->curl('image_sets', "_0000014/$set_id");
    }
    */

    protected function addImagesToSet($set_id, $images)
    {
        if (is_array($images)) {
            $images = $this->convertArrayToCSV($images);
        }

        $this->body['urls_to_add'] = new CURLFile($images);

        return $this->curl('image_sets', "_0000014/$set_id");
    }

    protected function removeImagesFromSet($set_id, $images)
    {
        if (is_array($images)) {
            $images = $this->convertArrayToCSV($images);
        }

        $this->body['urls_to_delete'] = new CURLFile($images);

        return $this->curl('image_sets', "_0000014/$set_id");
    }
}
