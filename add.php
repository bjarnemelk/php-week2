
<?php

session_start();

require_once 'db.php'; // Zorgt ervoor dat de database connectie wordt gemaakt

$stmt = $dbh->query("SELECT name FROM genres");
$genres = $stmt->fetchAll(PDO::FETCH_ASSOC); // Haalt alle genres op uit de database

$stmt = $dbh->query("SELECT name FROM directors");
$directors = $stmt->fetchAll(PDO::FETCH_ASSOC); // Haalt alle directors op


$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

$message = '';

if ($error === 'invalid_year') {
    $message = 'Invalid year. Please enter a 4-digit year between 1888 and ' . ((int) date('Y') + 5) . '.';
} elseif ($error === 'empty_fields') {
    $message = 'Please fill in all fields.';
} elseif ($error === 'director_exists') {
    $message = 'Director already exists.';
} elseif ($error === 'invalid_director_name') {
    $message = 'Invalid director name. Please use only letters and spaces.';
} elseif ($error === 'invalid_genre_name') {
    $message = 'Invalid genre name. Please use only letters and spaces.';
} elseif ($error === 'genre_exists') {
    $message = 'Genre already exists.';
}

if ($success === 'Director_added') {
    $message = 'Director added successfully.';
} elseif ($success === 'Film_added') {
    $message = 'Film added successfully.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Data</title>
</head>

<body class="add-film-page">

    <header>
        <nav>
            <a href="index.php">Home</a>
            <h1>Niet Letterboxd</h1>
        </nav>
    </header>

    <div class="forms">

        <datalist id="genres">
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo htmlspecialchars($genre['name']); ?>">
            <?php endforeach; ?>
        </datalist>

        <datalist id="directors">
            <?php foreach ($directors as $director): ?>
                <option value="<?php echo htmlspecialchars($director['name']); ?>">
            <?php endforeach; ?>
        </datalist>

        <form method="POST" action="add_genre_process.php">
            <label for="genre_name">Add Genre:</label>
            <input type="text" id="genre_name" name="genre_name" required>
            <button type="submit">Add Genre</button>
        </form>

        <form method="POST" action="add_process.php">

            <label for="title">Title:</label>
            <input type="text" list="titles" id="title" name="title"
                   value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>"
                   required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year"
                   value="<?php echo htmlspecialchars($old['year'] ?? ''); ?>"
                   required>

            <label for="director">Director:</label>
            <input type="text" id="director" name="director"
                   list="directors"
                   value="<?php echo htmlspecialchars($old['director'] ?? ''); ?>"
                   required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre"
                   list="genres"
                   value="<?php echo htmlspecialchars($old['genre'] ?? ''); ?>"
                   required>

            <button type="submit">Add Film</button>

        </form>

      
        <form method="POST" action="add_director_process.php">
            <label for="director_name">Add Director:</label>
            <input type="text" id="director_name" name="director_name" required>
            <button type="submit">Add Director</button>
        </form>

    </div>

    <?php if ($message): ?>
        <p style="color: red;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

</body>
</html>
