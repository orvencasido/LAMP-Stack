# 📥 PHP MySQL Number Submission App on Ubuntu EC2 Set Up a LAMP Stack (Linux, Apache, MySQL, PHP)

This project demonstrates how to build and deploy a basic PHP web application that accepts number input, stores it in a MySQL database, and displays the submitted values. The setup is done on an AWS EC2 instance running Ubuntu.

---

## 🛠 Tools & Technologies

- Ubuntu (Linux)
- AWS EC2 Instance
- Apache Web Server
- PHP
- MySQL Server
- mySQL (optional)
- SSH (Secure Shell)

---

## 🚀 Project Overview

- This project includes:
- Installing Apache, PHP, and MySQL (LAMP Stack)
- Creating a MySQL database and table
- Writing a basic PHP script to submit and retrieve numbers
- Viewing data through the browser

---

## 📋 Step-by-Step Guide

### ✅ 1. Install LAMP Stack
```bash
sudo apt update
sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server -y
```

### ✅ 2. Secure MySQL and Create Database
```
sudo mysql_secure_installation

# Then log in to MySQL:
sudo mysql -u root -p

# Inside MySQL:
CREATE DATABASE number_db;
USE number_db;
CREATE TABLE numbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value INT NOT NULL
);
EXIT;
```

### ✅ 3. Create PHP Script
Create a file at `/var/www/html/index.php`:
```
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

```

### ✅ 4. View in Browser
```
http://your-ip/index.ph

```

## 📸 Example Output
```
Enter a Number:
[ 23 ] [Submit]

Submitted Numbers:
23
12
5
```

## 📎 Commands Reference
- `sudo apt install apache2` – Install Apache web server
- `sudo apt install php` – Install PHP
- `sudo apt install mysql-server` – Install MySQL
- `mysql -u root -p` – Access MySQL shell
- `CREATE DATABASE` – Create a new MySQL database
- `CREATE TABLE` – Create a table inside the database

## 💡 Real-World Use Cases
- Practice full-stack development with PHP and MySQL
- Learn how data flows from web forms to databases
- Use EC2 to simulate real deployment environments

## 📚 Learning Outcomes
- Set up and configure a LAMP stack
- Write basic PHP scripts for database interaction
- Understand form submission and data persistence
- Deploy and test apps on cloud servers (EC2)

## 🧑‍💻 Author
Orven Casido – techwithorven.xyz
