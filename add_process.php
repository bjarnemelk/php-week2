<?php

session_start();

require_once 'db.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

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

    $stmt = $dbh->prepare( // Bereidt de Query voor om de gegevens in de database te zetten
        "INSERT INTO films (title, year, director, genre) 
         VALUES (:title, :year, :director, :genre)"
    );

    $stmt->bindParam(':title', $title); // Bindt de gegevens aan de Query
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':director', $director);
    $stmt->bindParam(':genre', $genre);

    $stmt->execute(); // Voert het uit

    header("Location: index.php"); // Stuurt de gebruiker terug naar de hoofdpagina
    exit();

} else {
    header("Location: add.php");
    exit();
}

?>