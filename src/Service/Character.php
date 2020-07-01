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
        $this->generateHeadCharacter($data);
        //alpha & transparency
        $dest_img = imagecreatetruecolor(35, 86);

        imagesavealpha($dest_img, true);
        $trans_background = imagecolorallocatealpha($dest_img, 0, 0, 0, 127);

        //fill the image with a transparent background
        imagefill($dest_img, 0, 0, $trans_background);
        $name = str_replace('/persona/creator/', '', $data['persona']);
        $color = substr($name, -8, 4);

        $name = substr($name, 0, 1) . $color .'-';

        $dir = $color.'/';
        $img_bo = imagecreatefrompng(getcwd() . $data['persona']);
        imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 35, 86);
        unset($data['persona']);

        foreach ($data as $key => $value) {
            if ('starz' === $key) {
                $img_bo = imagecreatefrompng(getcwd() . $value->getUrlHead());
                imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 35, 86);
                $value = $value->getId();
            }
            else {
                if (file_exists(getcwd() . '/persona/creator/' . $key . '/' . $value . '.png') && $value != '0000') {
                    $img_bo = imagecreatefrompng(getcwd() . '/persona/creator/' . $key . '/' . $value . '.png');
                    imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 35, 86);
                }
            }

            $name = $name . $value . '-';
            $dir = $dir . $value . '/';
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

    public function generateHeadCharacter($data)
    {
        return;
        //alpha & transparency
        $dest_img = imagecreatetruecolor(35, 42);

        imagesavealpha($dest_img, true);
        $trans_background = imagecolorallocatealpha($dest_img, 0, 0, 0, 127);

        //fill the image with a transparent background
        imagefill($dest_img, 0, 0, $trans_background);

        $name = str_replace('/persona/creator/', '', $data['persona']);
        $color = substr($name, -8, 4);

        $name = 'head-' . $color .'-';
        $dir = $color .'/';

        $head = str_replace('women', 'head', $data['persona']);
        $head = str_replace('men', 'head', $head);

        $img_bo = imagecreatefrompng(getcwd() . $head);
        imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 35, 42);
        unset($data['persona']);

        foreach ($data as $key => $value) {
            if(file_exists(getcwd() . '/persona/creator/' . $key . '/' . $value . '.png') && $value != '0000') {
                if ($key === 'mouth' || $key === 'nose' || $key === 'hair' || $key === 'eyes' || $key === 'hat') {
                    $img_bo = imagecreatefrompng(getcwd() . '/persona/creator/' . $key . '/' . $value . '.png');
                    imagecopy($dest_img, $img_bo, 0, 0, 0, 0, 35, 42);
                }
            }

            $name = $name . $value . '-';
            $dir = $dir . $value . '/';
        }

        $name = substr($name, 0, -1);
        $dir = getcwd() .'/persona/creator/p/'. substr($dir, 0, 15);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //save the png image
        imagepng($dest_img, $dir . $name .'.png');

        return $name;
    }
}