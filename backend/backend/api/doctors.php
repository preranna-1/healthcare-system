<?php
require_once '../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM DOCTOR";
    $result = $conn->query($sql);
    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    echo json_encode($doctors);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO DOCTOR (DoctorID, CenterID, Name, Specialization, Qualification, YearsOfExperience, OverallRating) 
            VALUES ('{$data['DoctorID']}', '{$data['CenterID']}', '{$data['Name']}', '{$data['Specialization']}', '{$data['Qualification']}', {$data['YearsOfExperience']}, {$data['OverallRating']})";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Doctor created']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
