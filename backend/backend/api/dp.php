<?php
$host = "localhost";   // XAMPP default
$user = "root";        // default user
$pass = "";            // default password (empty unless you set one)
$db   = "hospital_db"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

