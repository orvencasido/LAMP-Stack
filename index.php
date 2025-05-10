<?php
// Database connection details
$host = 'localhost';
$db = 'number_db';
$user = 'phpuser';
$pass = 'anyStrongPassword!123';

$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die('Connection Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Only run if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number = intval($_POST['number']);

    $stmt = $mysqli->prepare("INSERT INTO numbers (value) VALUES (?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $number);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: index.php");
    exit();
}

// Fetch all numbers from the table
$result = $mysqli->query("SELECT value FROM numbers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit a Number</title>
</head>
<body>
    <h1>Submit a Number</h1>
    <form action="index.php" method="POST">
        <label for="number">Enter a Number:</label>
        <input type="number" id="number" name="number" required>
        <button type="submit">Submit</button>
    </form>

    <h2>Submitted Numbers</h2>
    <ul>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['value']) . "</li>";
            }
        } else {
            echo "<li>No numbers submitted yet.</li>";
        }
        ?>
    </ul>
</body>
</html>
