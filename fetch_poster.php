<?php 

require_once 'db.php';
require_once 'includes/tmdb.php';

$stmt = $dbh->query("SELECT id, title, year FROM films WHERE tmdb_id IS NULL");
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($films as $film) {
    $tmdbId = getTmdbId($film['title'], $film['year']);

    if ($tmdbId) {
        $stmtUpdate = $dbh->prepare("UPDATE films SET tmdb_id = :tmdb_id WHERE id = :id");
        $stmtUpdate->bindParam(':tmdb_id', $tmdbId);
        $stmtUpdate->bindParam(':id', $film['id']);
        $stmtUpdate->execute();
    }


    $posterPath = getPosterPath($tmdbId);

    if ($posterPath) {
        $stmtUpdatePoster = $dbh->prepare("UPDATE films SET poster_path = :poster_path WHERE id = :id");
        $stmtUpdatePoster->bindParam(':poster_path', $posterPath);
        $stmtUpdatePoster->bindParam(':id', $film['id']);
        $stmtUpdatePoster->execute();
    }

    usleep(500000); // Wacht 0,5 seconden om de API niet te overbelasten
}