<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Boumz\BoumzBundle\Entity\Persona;

class Character {

    public function __construct()
    {
    }

    public function generateCharacter($data)
    {
        //alpha & transparency
        $dest_img = imagecreatetruecolor(36, 83);

        imagesavealpha($dest_img, true);
        //create a fully transparent background (127 means fully transparent)
        $trans_background = imagecolorallocatealpha($dest_img, 0, 0, 0, 127);

        //fill the image with a transparent background
        imagefill($dest_img, 0, 0, $trans_background);

        $name = '';
        $dir = '';
        $img_bo = imagecreatefrompng(getcwd() . $data['persona']);
        imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 36, 83);
        unset($data['persona']);

        foreach ($data as $key => $value) {
            $img_bo = imagecreatefrompng(getcwd() . '/persona/creator/' . $key . '/' . $value . '.png');
            imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 36, 83);
            $name = $name . $value .'-';
            $dir = $dir . $value .'/';
        }
        $name = substr($name, 0, -1);
        $dir = getcwd() .'/persona/creator/p/'. substr($dir, 0, 15);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //save the png image
        imagepng($dest_img, $dir .'/'. $name .'.png');

        return $name;
    }
}