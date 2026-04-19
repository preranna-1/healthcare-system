<?php
require_once '../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM Bed WHERE IsAvailable = TRUE";
    $result = $conn->query($sql);
    $beds = [];
    while ($row = $result->fetch_assoc()) {
        $beds[] = $row;
    }
    echo json_encode($beds);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO Bed (BedID, CenterID, BedType, BedName, IsAvailable, LastUpdate) 
            VALUES ({$data['BedID']}, '{$data['CenterID']}', '{$data['BedType']}', '{$data['BedName']}', 1, NOW())";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Bed created']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
