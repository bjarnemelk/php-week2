<?php

$hostname = 'localhost';
$username = 'root';
$password = '';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=films", $username, $password); // Regelt connectie met database
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }

?>