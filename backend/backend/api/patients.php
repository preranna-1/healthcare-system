<?php
require_once '../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT * FROM PATIENT";
    $result = $conn->query($sql);
    $patients = [];
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    echo json_encode($patients);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $sql = "INSERT INTO PATIENT (PatientID, UserID, DOB, Gender, BloodGroup, DiseaseType) 
            VALUES ('{$data['PatientID']}', '{$data['UserID']}', '{$data['DOB']}', '{$data['Gender']}', '{$data['BloodGroup']}', '{$data['DiseaseType']}')";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Patient created']);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
