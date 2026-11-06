<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        exit('Invalid request.'); 
    }

    $stmtLinks = $dbh->prepare("DELETE FROM films WHERE id = :id");
    $stmtGenres = $dbh->prepare("DELETE FROM film_genres WHERE film_id = :id");
    $stmtLinks->bindparam(':id', $id);
    $stmtGenres->bindparam(':id', $id);
    $stmtLinks->execute();
    $stmtGenres->execute();
    exit();

    header("Location: index.php");
}

?>