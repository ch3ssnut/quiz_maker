<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ImageResize {

    private const MAX_WIDTH = 1000;
    private const MAX_HEIGHT = 650;

    private $params;

    public function __construct(ParameterBagInterface $params) {
        $this->params = $params;
    }

    public function resizeImage($fileName): void {
        // setting path with filename
        $path = $this->params->get('image_directory'). '/' . $fileName;
        
        // width or height is dependent on ratio
        list($iwidth, $iheight) = getimagesize($path);
        $ratio = $iwidth / $iheight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }
        
        // resize and save image
        $imagine = new Imagine;
        $image = $imagine->open($path);
        $size = new Box($width, $height);
        $image->resize($size)->save($path);


        return; 

    }

}


