<?php
// feedback.php

// Create a connection to the database
$host = 'localhost';
$db = 'healthcare_system';
$user = 'your_username';
$password = 'your_password';

$conn = new mysqli($host, $user, $password, $db);

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// GET endpoint for retrieving feedback
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM feedback ORDER BY created_at DESC";
    $result = $conn->query($query);
    $feedbacks = [];

    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }

    echo json_encode($feedbacks);
}

// POST endpoint for submitting feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO feedback (name, message) VALUES (?, ?)");
    $stmt->bind_param('ss', $name, $message);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit feedback.']);
    }
    $stmt->close();
}

$conn->close();
?>