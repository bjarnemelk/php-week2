<?php

session_start();

require_once 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Controleert of het formulier is ingevuld

    $title = trim($_POST['title'] ?? ''); // Haalt de gegevens op en haalt spaties aan het begin/einde weg
    $year = trim($_POST['year'] ?? '');
    $director = trim($_POST['director'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $genreNames =explode(',', $genre); // Splitst de genres op in een array
    $genreNames = array_map('trim', $genreNames); // Haalt spaties aan het begin/einde weg van de genres
    $genreNames = array_filter($genreNames); // Verwijdert lege genres

    if ($title === '' || $year === '' || $director === '' ||$genre === ''|| empty($genreNames)) {
        $_SESSION['error'] = 'empty_fields'; // Stopt als er velden leeg zijn
        $_SESSION['old'] = compact('title', 'year', 'director', 'genre');
        header("Location: add.php");
        exit();
    }

    $maxYear = (int) date('Y') + 5; // Maximum jaar voor preg_match

    if (!preg_match('/^[0-9]{4}$/', $year) || (int) $year < 1888 || (int) $year > $maxYear) {
    $_SESSION['error'] = 'invalid_year'; // Stopt als het jaar niet geldig is
    $_SESSION['old'] = compact('title', 'year', 'director', 'genre');
    header("Location: add.php"); 
    exit();
}

$stmtdirector = $dbh->prepare("SELECT id FROM directors WHERE name = :director");


$stmtdirector->bindParam(':director', $director);


$stmtdirector->execute();


$directorId = $stmtdirector->fetchColumn();


if ($directorId === false) {
    $stmt = $dbh->prepare("INSERT INTO directors (name) VALUES (:name)");
    $stmt->bindParam(':name', $director);
    $stmt->execute();
    $directorId = $dbh->lastInsertId(); 
}


    $stmt = $dbh->prepare( // Bereidt de Query voor om de gegevens in de database te zetten
        "INSERT INTO films (title, year, director_id) 
         VALUES (:title, :year, :director_id)"
    );

    

    $stmt->bindParam(':title', $title); // Bindt de gegevens aan de Query
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':director_id', $directorId);

    $stmt->execute(); // Voert het uit
    $filmId = $dbh->lastInsertId(); // Haalt het laatst ingevoerde ID op
    $stmtGenreSelect = $dbh->prepare("SELECT id FROM genres WHERE name = :name"); // Bereidt de Query voor om de genres op te halen
    $stmtGenre = $dbh->prepare("INSERT INTO film_genres (film_id, genre_id) VALUES (:film_id, :genre_id)"); // Bereidt de Query voor om de genres in de database te zetten
    foreach ($genreNames as $genreName) { // Loopt door alle genres heen
        $stmtGenreSelect->bindParam(':name', $genreName);
        $stmtGenreSelect->execute();
        $genreId = $stmtGenreSelect->fetchColumn();

        if ($genreId === false) { // Als het genre nog niet bestaat, voeg het toe aan de genres tabel
            $stmtInsertGenre = $dbh->prepare("INSERT INTO genres (name) VALUES (:name)");
            $stmtInsertGenre->bindParam(':name', $genreName);
            $stmtInsertGenre->execute();
            $genreId = $dbh->lastInsertId(); // Haalt het laatst ingevoerde ID op
        }

        $stmtGenre->bindParam(':film_id', $filmId);
        $stmtGenre->bindParam(':genre_id', $genreId);
        $stmtGenre->execute(); // Voert het uit
    }


    $_SESSION['success'] = 'Film_added'; 

    header("Location: index.php"); // Stuurt de gebruiker terug naar de hoofdpagina
    exit();

} else {
    header("Location: add.php");
    exit();
}

?>