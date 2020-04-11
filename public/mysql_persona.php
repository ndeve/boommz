<?php

require ('../vendor/behat/transliterator/src/Behat/Transliterator/Transliterator.php');

set_time_limit(1000);
// Name of the file
$filename = 'dump_boumz.sql';

// Connect to MySQL server
$dbh = new PDO('mysql:host=mysql;dbname=boommz', 'boommz', 'password');

try {
    $result = $dbh->query("SELECT * FROM `persona`");

    foreach ($result as $data) {

        $personaId = $data['id'];
        $hash = hash('ripemd160', $personaId);

        $path = '';
        $i = 0;
        foreach (str_split($hash) as $letter) {

            $path .= $letter .'/';
            $i++;
            //mkdir('persona/'. $path, 0777, true);
            if ($i == 3) {
                $dest = 'persona/'. $path . \Behat\Transliterator\Transliterator::urlize($data['name']) .'-'. $personaId .'.png';

                copy('p/'. str_replace('gif', 'png', $data['url']), $dest);
                $dbh->query("UPDATE `persona` SET `path` = '$path' WHERE `persona`.`id` = $personaId");
                break;
            }
        }

    }

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

        // Reset temp variable to empty
