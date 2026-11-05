<?php

require_once 'db.php'; // Zorgt ervoor dat de database connectie wordt gemaakt

$sort = $_GET['sort'] ?? '';
if ($sort === 'title') {
    $stmt = $dbh->query("select * from films order by title"); // Alle sorteeropties, gebruikt statement, daarna haalt hij alle films op en bepaalt hij gebaseerd op de sorteeroptie hoe hij ze moet sorteren
} elseif ($sort === 'title_desc') {
    $stmt = $dbh->query("select * from films order by title desc");
} elseif ($sort === 'year') {
    $stmt = $dbh->query("select * from films order by year desc");
} elseif ($sort === 'year_asc') {
    $stmt = $dbh->query("select * from films order by year");
} elseif ($sort === 'director') {
    $stmt = $dbh->query("select * from films order by director");
} elseif ($sort === 'director_desc') {
    $stmt = $dbh->query("select * from films order by director desc");
} else {
    $stmt = $dbh->query("select * from films");
}
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

