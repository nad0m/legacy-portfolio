<?php


$host = "localhost";
$dbname = "nguy7791"; //change this to your otterID
$username = "nguy7791"; //change this to your otter ID
$password = "myPassword"; //change this to your database account password

//establishes database connection
$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

//shows errors when connecting to database
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>