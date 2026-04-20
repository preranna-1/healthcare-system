<?php
$host = "localhost";
$user = "root"; // default XAMPP user
$pass = "";     // leave empty unless you set a password
$db   = "hospital_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
