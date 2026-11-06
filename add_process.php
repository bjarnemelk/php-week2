<?php

session_start();

require_once 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Controleert of het formulier is ingevuld

    $title = trim($_POST['title'] ?? ''); // Haalt de gegevens op en haalt spaties aan het begin/einde weg
    $year = trim($_POST['year'] ?? '');
    $director = trim($_POST['director'] ?? '');
    $genre = trim($_POST['genre'] ?? '');

    if ($title === '' || $year === '' || $director === '' || $genre === '') {
        exit('Please fill in all fields.'); // Stopt als er velden leeg zijn
    }

    $maxYear = (int) date('Y') + 5; // Maximum jaar voor preg_match

    if (!preg_match('/^[0-9]{4}$/', $year) || (int) $year < 1888 || (int) $year > $maxYear) {
    $_SESSION['error'] = 'invalid_year'; // Stopt als het jaar niet geldig is
    $_SESSION['old'] = compact('title', 'year', 'director', 'genre');
    header("Location: add.php"); 
    exit();
}

$stmtdirector = $dbh->prepare("SELECT id FROM directors WHERE name = :director");
$stmtgenre = $dbh->prepare("SELECT id FROM genres WHERE name = :genre");

$stmtdirector->bindParam(':director', $director);
$stmtgenre->bindParam(':genre', $genre);

$stmtdirector->execute();
$stmtgenre->execute();

$directorId = $stmtdirector->fetchColumn();
$genreId = $stmtgenre->fetchColumn();

if ($directorId === false) {
    $stmt = $dbh->prepare("INSERT INTO directors (name) VALUES (:name)");
    $stmt->bindParam(':name', $director);
    $stmt->execute();
    $directorId = $dbh->lastInsertId(); 
}

if ($genreId === false) {
    $stmt = $dbh->prepare("INSERT INTO genres (name) VALUES (:name)");
    $stmt->bindParam(':name', $genre);
    $stmt->execute();
    $genreId = $dbh->lastInsertId();
}

    $stmt = $dbh->prepare( // Bereidt de Query voor om de gegevens in de database te zetten
        "INSERT INTO films (title, year, director_id, genre_id) 
         VALUES (:title, :year, :director_id, :genre_id)"
    );

    $stmt->bindParam(':title', $title); // Bindt de gegevens aan de Query
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':director_id', $directorId);
    $stmt->bindParam(':genre_id', $genreId);

    $stmt->execute(); // Voert het uit
    $_SESSION['success'] = 'Film_added'; 

    header("Location: index.php"); // Stuurt de gebruiker terug naar de hoofdpagina
    exit();

} else {
    header("Location: add.php");
    exit();
}

?>