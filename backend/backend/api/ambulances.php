<?php
require_once '../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM AMBULANCE WHERE IsAvailable = TRUE";
    $result = $conn->query($sql);
    $ambulances = [];
    while ($row = $result->fetch_assoc()) {
        $ambulances[] = $row;
    }
    echo json_encode($ambulances);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO AMBULANCE (AmbulanceID, CenterID, RegistrationNumber, DriverName, DriverPhone, Type, IsAvailable, CurrentLatitude, CurrentLongitude) 
            VALUES ('{$data['AmbulanceID']}', '{$data['CenterID']}', '{$data['RegistrationNumber']}', '{$data['DriverName']}', '{$data['DriverPhone']}', '{$data['Type']}', 1, {$data['CurrentLatitude']}, {$data['CurrentLongitude']})";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Ambulance created']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
