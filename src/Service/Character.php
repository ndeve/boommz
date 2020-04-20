<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Boumz\BoumzBundle\Entity\Persona;

class Character {

    public function __construct()
    {
    }

    public function generateCharacterToAvatar(Persona $persona)
    {
        preg_match('$\/([^/]*).png$', $persona->getUrl(), $match);

        $a = explode('-', $match[1]);
        //se-co-to-le-he-mo-no-ey-eb-ha
        $data['se'] = $a[0];
        $data['co'] = $a[1];
        $data['to'] = $a[2];
        $data['le'] = $a[3];
        $data['he'] = $a[4];
        $data['mo'] = $a[5];
        $data['no'] = $a[6];
        $data['ey'] = $a[7];
        $data['eb'] = $a[8];
        $data['ha'] = $a[9];


        //alpha & transparency
        $img_20x20 = imagecreatetruecolor(20, 20);
        imagesavealpha($img_20x20, true);
        $trans_background = imagecolorallocatealpha($img_20x20, 0, 0, 0, 127);
        imagefill($img_20x20, 0, 0, $trans_background);

        $img_bo = imagecreatefrompng('http://fr.boumz.com/images/mp/bo/'. $data['co'] . $data['se'] .'.png');
        $img_to = imagecreatefrompng('http://fr.boumz.com/images/mp/to/'. $data['to'] .'.png');
        $img_le = imagecreatefrompng('http://fr.boumz.com/images/mp/le/'. $data['le'] .'.png');
        $img_he = imagecreatefrompng('http://fr.boumz.com/images/mp/he/'. $data['co'] . $data['he'] .'.png');
        $img_no = imagecreatefrompng('http://fr.boumz.com/images/mp/no/'. $data['no'] .'.png');
        $img_mo = imagecreatefrompng('http://fr.boumz.com/images/mp/mo/'. $data['mo'] .'.png');
        $img_ey = imagecreatefrompng('http://fr.boumz.com/images/mp/ey/'. $data['ey'] .'.png');
        $img_eb = imagecreatefrompng('http://fr.boumz.com/images/mp/eb/'. $data['eb'] .'.png');
        $img_ha = imagecreatefrompng('http://fr.boumz.com/images/mp/ha/'. $data['ha'] .'.png');

        //merge all images
        imagecopy($img_20x20, $img_he, 0, 0, 3, 3, 27, 44);
        imagecopy($img_20x20, $img_no, 0, 0, 3, 3, 27, 44);
        imagecopy($img_20x20, $img_mo, 0, 0, 3, 3, 27, 44);
        imagecopy($img_20x20, $img_ey, 0, 0, 3, 3, 27, 44);
        imagecopy($img_20x20, $img_eb, 0, 0, 3, 3, 27, 44);
        imagecopy($img_20x20, $img_ha, 0, 0, 3, 3, 27, 44);

        imagepng($img_20x20, $user->getPath(0) . $user->getUsernameCanonical() .'_20.png');


        $img_50x50 = imagecreatetruecolor(50, 50);
        imagesavealpha($img_50x50, true);
        $trans_background = imagecolorallocatealpha($img_50x50, 0, 0, 0, 127);
        imagefill($img_50x50, 0, 0, $trans_background);

        imagecopy($img_50x50, $img_bo, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_to, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_le, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_he, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_no, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_mo, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_ey, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_eb, 12, 2, 0, 0, 27, 44);
        imagecopy($img_50x50, $img_ha, 12, 2, 0, 0, 27, 44);

        //save the png image
        imagepng($img_50x50, $user->getPath(0) . $user->getUsernameCanonical() .'_50.png');

        $img_00x00 = imagecreatetruecolor(100, 100);
        imagesavealpha($img_00x00, true);
        $trans_background = imagecolorallocatealpha($img_00x00, 0, 0, 0, 127);
        imagefill($img_00x00, 0, 0, $trans_background);

        imagecopyresized($img_00x00, $img_50x50, 0, 0, 0, 0, 100, 100, 50, 50);

        //save the png image
        imagepng($img_00x00, $user->getPath(0) . $user->getUsernameCanonical() .'.png');

        $user->setPicture('png');

        $this->em->persist($user);
        $this->em->flush();
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