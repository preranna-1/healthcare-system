<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        if (isset($request[2]) && !empty($request[2])) {
            $patientID = $conn->real_escape_string($request[2]);
            $sql = "SELECT * FROM PATIENT WHERE PatientID = '$patientID'";
        } else {
            $sql = "SELECT * FROM PATIENT";
        }
        
        $result = $conn->query($sql);
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        ApiResponse::success($patients, 'Patients retrieved');
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $PatientID = $data['PatientID'] ?? null;
        $UserID = $data['UserID'] ?? null;
        $DOB = $data['DOB'] ?? null;
        $Gender = $data['Gender'] ?? null;
        $BloodGroup = $data['BloodGroup'] ?? null;
        $DiseaseType = $data['DiseaseType'] ?? null;
        
        if (!$PatientID) {
            ApiResponse::error('PatientID is required', 400);
        }
        
        $sql = "INSERT INTO PATIENT (PatientID, UserID, DOB, Gender, BloodGroup, DiseaseType) 
                VALUES ('$PatientID', '$UserID', '$DOB', '$Gender', '$BloodGroup', '$DiseaseType')";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['PatientID' => $PatientID], 'Patient created successfully', 201);
        } else {
            ApiResponse::error('Error creating patient', 500);
        }
    }
    else {
        ApiResponse::error('Method not allowed', 405);
    }
    
} catch (Exception $e) {
    ApiResponse::error('Server error: ' . $e->getMessage(), 500);
}

$conn->close();
?>
