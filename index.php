<?php

require_once 'db.php'; // Zorgt ervoor dat de database connectie wordt gemaakt

$baseQuery = "SELECT films.*, genres.name AS genre, directors.name AS director
              FROM films
              LEFT JOIN genres ON films.genre_id = genres.id
              LEFT JOIN directors ON films.director_id = directors.id";

$sort = $_GET['sort'] ?? '';
$orderBy = '';
if ($sort === 'title') {
    $orderBy = 'ORDER BY films.title'; // Alle sorteeropties, gebruikt statement, daarna haalt hij alle films op en bepaalt hij gebaseerd op de sorteeroptie hoe hij ze moet sorteren
} elseif ($sort === 'title_desc') {
    $orderBy = 'ORDER BY films.title DESC';
} elseif ($sort === 'year') {
    $orderBy = 'ORDER BY films.year DESC';
} elseif ($sort === 'year_asc') {
    $orderBy = 'ORDER BY films.year ASC';
} elseif ($sort === 'director') {
    $orderBy = 'ORDER BY films.director_id';
} elseif ($sort === 'director_desc') {
    $orderBy = 'ORDER BY films.director_id DESC';
} else {
    $orderBy = '';
}

$stmt = $dbh->query($baseQuery . ' ' . $orderBy);

$films = $stmt->fetchAll(PDO::FETCH_ASSOC); // Haalt alle films op uit de database en slaat ze op in een array



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav>
        <a href="add.php">Add Film</a>
        <h1>Niet Letterboxd</h1>
        <form method="GET">
    <select name="sort" onchange="this.form.submit()">
        <option value="">Sort by...</option>
        <option value="title">Title (A-Z)</option>
        <option value="title_desc">Title (Z-A)</option>
        <option value="year">Year (newest first)</option>
        <option value="year_asc">Year (oldest first)</option>
        <option value="director">Director (A-Z)</option>
        <option value="director_desc">Director (Z-A)</option>
    </select>
</form>
<?php echo "<h4 class = \"count\">Films in Database: " . count($films) . "</h4>"; ?> 
    </nav>
</header>

<main>
    <div class="films">

        <?php
        foreach ($films as $film) {
        ?>

            <section class="filmcard">
                <h1><?= $film["title"] ?></h1>
                <p>Year: <?= $film["year"] ?></p>
                <p>Director: <?= $film["director"] ?></p>
                <p>Genre: <?= $film["genre"] ?></p>
                <form method="POST" action="delete_process.php">
                    <input type="hidden" name="id" value="<?= $film["id"] ?>">
                    <button type="submit">🗑️</button>
                </form>
            </section>

        <?php
        }
        ?>

    </div>
</main>
    
</body>
</html>

