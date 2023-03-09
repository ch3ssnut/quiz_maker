<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageSaver {

    private $slugger;
    private $params;

    public function __construct(SluggerInterface $slugger, ParameterBagInterface $params) {
        $this->slugger = $slugger;
        $this->params = $params;
    }

    public function saveImage($form) {
        $imageFile = $form->get('image')->getData();

        if($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-'.uniqid() . '.' . $imageFile->guessExtension();


            try {
                $imageFile->move(
                    $this->params->get('image_directory'),
                    $newFilename

                );
            }

            catch (FileException $e) {
                
            }

            return $newFilename;

        }

    }
}

