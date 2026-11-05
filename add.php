<?php

session_start();

require_once 'db.php'; // Zorgt ervoor dat de database connectie wordt gemaakt


$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']); 
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']); 

$message = '';
if ($error === 'invalid_year') {
    $message = 'Invalid year. Please enter a 4-digit year between 1888 and ' . ((int) date('Y') + 5) . '.';
} elseif ($error === 'empty_fields') {
    $message = 'Please fill in all fields.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST" action="add_process.php">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required>
    <label for="year">Year:</label>
    <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($old['year'] ?? ''); ?>" required>
    <label for="director">Director:</label>
    <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($old['director'] ?? ''); ?>" required>
    <label for="genre">Genre:</label>
    <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($old['genre'] ?? ''); ?>"  required>
    <button type="submit">Add Film</button>

</form>
<?php if ($message): ?>
    <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
</body>
</html>