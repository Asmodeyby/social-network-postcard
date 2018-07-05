<?php
/**
 * Created by PhpStorm.
 * User: Asmod
 * Date: 25.06.2018
 * Time: 0:19
 */

use Asmodeyby\Postcard\Postcard;
use PHPUnit\Framework\TestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Intervention\Image\File;


class PostcardTest extends LaravelTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub


    }

    public function testPostcard() {
        $file = new File;
        $file->setFileInfoFromPath("tests/assets/images/anime.jpg");

        $file2 = new File;
        $file2->setFileInfoFromPath("tests/assets/images/anime2.jpg");

        $logo = new File;
        $logo->setFileInfoFromPath("tests/assets/images/logo.png");

        $postcard = Postcard::make(720, 720);

        $layout = new \Asmodeyby\Postcard\Layout\ImageLayout();
        $layout->setImage($file->basePath(), \Asmodeyby\Postcard\Layout\ImageLayout::CONTAIN_IMAGE);
        $layout->setLayerPosition(30,30);
        $layout->setOpacity(50);

        $layout2 = new \Asmodeyby\Postcard\Layout\ImageLayout();
        $layout2->setImage($file2->basePath(), \Asmodeyby\Postcard\Layout\ImageLayout::CANVAS_IMAGE);


        $layout3 = new \Asmodeyby\Postcard\Layout\LinearGradientLayout();
        $layout3->setGradient(70,70, 720, 720, "#ffffff00", "#ff0000");
        $layout3->setLayerPosition(-50, -50);
        $layout3->setOpacity(50);



        $postcard->addLayout($layout2);
        $postcard->addLayout($layout);
        $postcard->addLayout($layout3);

        $postcard->render()->save("tests/output/3.jpg");

        $this->assertTrue(true, "test postcard fail");
    }

    /**
     * Create a mock of a Storage disk.
     *
     * Usage:
     *     ```
     *     $storage = $this->mockStorageDisk('my-disk');
     *     $storage->shouldReceive('get')->once()->andReturn('test');
     *
     *     // test
     *     Storage::disk('my-disk')->get('file.txt');
     *     ```
     *
     * @param  String $disk Optional
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function mockStorageDisk($disk = 'mock')
    {
        Storage::extend('mock', function () {
            return \Mockery::mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        });
        Config::set('filesystems.disks.' . $disk, ['driver' => 'mock']);
        Config::set('filesystems.default', $disk);
        return Storage::disk($disk);
    }
}