<?php

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $directorName = trim($_POST['director_name'] ?? '');

    if ($directorName === '') {
        exit('Please fill in the director name.');
    }

    $stmt = $dbh->prepare("SELECT id FROM directors WHERE name = :name");
    $stmt->bindParam(':name', $directorName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'director_exists';
        header("Location: add.php");
        exit();
    }

    $stmt = $dbh->prepare("INSERT INTO directors (name) VALUES (:name)");
    $stmt->bindParam(':name', $directorName);
    $stmt->execute();

    header("Location: add.php");
    exit();
} else {
    header("Location: add.php");
    exit();
}

?>