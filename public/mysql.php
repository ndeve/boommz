<?php
set_time_limit(1000);
// Name of the file
$filename = 'dump_boumz.sql';

// Connect to MySQL server
$dbh = new PDO('mysql:host=mysql;dbname=boommz', 'boommz', 'password');

// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line) {
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '') {
        continue;
    }

    // Add this line to the current segment
    $templine .= $line;
    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';') {
        // Perform the query
        try {
            echo $templine ."<br/>";
            $dbh->query($templine);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }

        // Reset temp variable to empty
        $templine = '';
    }
}
echo "Tables imported successfully";
?>