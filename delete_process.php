<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        exit('Invalid request.'); 
    }

    $stmt = $dbh->prepare("DELETE FROM films WHERE id = :id");
    $stmt->bindparam(':id', $id);
    $stmt->execute();

    header("Location: index.php");
}

?>