<?php

// change to your database
$host = "localhost";
$dbname = "nguy7791";
$username = "nguy7791";
$password = "myPassword";

$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Setting Errorhandling to Exception
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
         $sql = "SELECT DISTINCT(county) FROM zip_code " .
                " WHERE state = :state AND county !='' ORDER BY county";
         $stmt = $dbConn->prepare($sql);
         $stmt->execute( array (":state"=>$_GET['state']));
         $results = $stmt->fetchAll();
        
         echo "{ \"counties\": [ ";
         foreach ($results as $record){
             echo "{\"county\":" . "\"" . $record['county'] . "\"},";
         }
         echo "{\"county\":" . "\"\"}";
          echo "] }";

?>