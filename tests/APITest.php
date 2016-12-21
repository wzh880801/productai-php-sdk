<?php

namespace ProductAI\Tests;

use PHPUnit\Framework\TestCase;
use ProductAI\API;

class APITest extends TestCase
{
    private $product_ai;

    public function setUp()
    {
        $this->product_ai = new API(ACCESS_KEY_ID, SECRET_KEY);
    }

    public function testBadMethodCall()
    {
        $this->expectException('BadMethodCallException');
        $this->product_ai->notExistsMethod();
    }

    public function testSearchImageByURL()
    {
        $result = $this->product_ai->searchImage('classify_fashion', '_0000001', 'http://www.sinaimg.cn/dy/slidenews/24_img/2013_13/40223_662671_794351.jpg');
        $this->assertEquals(0, $result['is_err']);
    }

    public function testSearchImageByFile()
    {
        $result = $this->product_ai->searchImage('classify_sleeve', '_0000002', '@'.__DIR__.'/test.jpg');
        $this->assertEquals(0, $result['is_err']);
    }

    public function testSearchImageByForm()
    {
        $this->expectException('OutOfBoundsException');
        $this->product_ai->searchImage('classify_color', '_0000003', '#test');
    }

    public function testAddImageToSet()
    {
        $result = $this->product_ai->addImageToSet(IMAGE_SET_ID, 'http://www.wed114.cn/jiehun/uploads/allimg/c130401/1364P42Q140-49539.jpg', 'test image');
        $this->assertArrayNotHasKey('error_code', $result);
    }

    public function testAddImagesToSet()
    {
        $result = $this->product_ai->addImagesToSet(IMAGE_SET_ID, [
            [
                'url' => 'http://images.yoka.com/pic/fashion/roadshow/2010/U162P1T117D149859F2577DT20100827201536.JPG',
                'meta' => 'test image 1',
            ],
            [
                'url' => 'http://www.people.com.cn/mediafile/pic/20130924/54/1190700219959357062.jpg',
                'meta' => 'test image 2',
            ],
        ]);
        $this->assertArrayNotHasKey('error_code', $result);
    }

    public function testRemoveImagesFromSet()
    {
        $result = $this->product_ai->removeImagesFromSet(IMAGE_SET_ID, [
            'http://images.yoka.com/pic/fashion/roadshow/2010/U162P1T117D149859F2577DT20100827201536.JPG',
            'http://www.people.com.cn/mediafile/pic/20130924/54/1190700219959357062.jpg',
        ]);
        $this->assertArrayNotHasKey('error_code', $result);
    }
}
