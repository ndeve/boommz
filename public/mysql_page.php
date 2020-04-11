<?php
set_time_limit(1000);
// Name of the file
$filename = 'dump_boumz.sql';

// Connect to MySQL server
$dbh = new PDO('mysql:host=mysql;dbname=boommz', 'boommz', 'password');

try {
    $result = $dbh->query("SELECT * FROM `comic`");

    foreach ($result as $data) {
        $comicId = $data['id'];

        var_dump($comicId);

        $result2 = $dbh->query("INSERT INTO `page` (`comic_id`, `title`, `order_page`, `date_creation`) VALUES ('$comicId', '', '0', '2020-04-10 00:03:44');");
        $pageId = $dbh->lastInsertId();

        $dbh->query("UPDATE `box` SET `page_id` = '$pageId' WHERE `box`.`comic_id` = $comicId");
    }

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

        // Reset temp variable to empty
