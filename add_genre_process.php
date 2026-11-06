<?php

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $genreName = trim($_POST['genre_name'] ?? '');

    if ($genreName === '') {
        $_SESSION['error'] = 'empty_fields';
        header("Location: add.php");
        exit();
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $genreName)) {
        $_SESSION['error'] = 'invalid_genre_name';
        header("Location: add.php");
        exit();
    }
    
    if ($stmt = $dbh->prepare("SELECT id FROM genres WHERE name = :name")) {
        $stmt->bindParam(':name', $genreName);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'genre_exists';
            header("Location: add.php");
            exit();
        }
    }
    
    $stmt = $dbh->prepare("INSERT INTO genres (name) VALUES (:name)");
    $stmt->bindParam(':name', $genreName);
    $stmt->execute();

    header("Location: add.php");
    exit();
} else {
    header("Location: add.php");
    exit();
}

?>