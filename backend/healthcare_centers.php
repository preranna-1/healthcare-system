<?php
require_once '../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM HEALTHCARE_CENTER";
    $result = $conn->query($sql);
    $centers = [];
    while ($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }
    echo json_encode($centers);
} 
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO HEALTHCARE_CENTER (CenterID, Name, Address, PhoneNumbers, Email, Latitude, Longitude, OperatingHours, OverallRating, CenterType) 
            VALUES ('{$data['CenterID']}', '{$data['Name']}', '{$data['Address']}', '{$data['PhoneNumbers']}', '{$data['Email']}', {$data['Latitude']}, {$data['Longitude']}, '{$data['OperatingHours']}', {$data['OverallRating']}, '{$data['CenterType']}')";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Healthcare center created']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
