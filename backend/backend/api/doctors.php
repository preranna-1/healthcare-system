<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        if (isset($request[2]) && $request[2] === 'center' && isset($request[3])) {
            // GET doctors by center
            $centerID = $conn->real_escape_string($request[3]);
            $sql = "SELECT * FROM DOCTOR WHERE CenterID = '$centerID'";
        } else if (isset($request[2]) && !empty($request[2])) {
            // GET single doctor
            $doctorID = $conn->real_escape_string($request[2]);
            $sql = "SELECT * FROM DOCTOR WHERE DoctorID = '$doctorID'";
        } else {
            // GET all doctors
            $sql = "SELECT * FROM DOCTOR";
        }
        
        $result = $conn->query($sql);
        
        if ($result) {
            $doctors = [];
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
            ApiResponse::success($doctors, 'Doctors retrieved');
        } else {
            ApiResponse::error('Error fetching doctors', 500);
        }
    }
    elseif ($method === 'POST') {
        // CREATE doctor
        $data = json_decode(file_get_contents('php://input'), true);
        
        $DoctorID = $data['DoctorID'] ?? null;
        $CenterID = $data['CenterID'] ?? null;
        $Name = $data['Name'] ?? null;
        $Specialization = $data['Specialization'] ?? null;
        $Qualification = $data['Qualification'] ?? null;
        $YearsOfExperience = $data['YearsOfExperience'] ?? 0;
        $OverallRating = $data['OverallRating'] ?? 0;
        
        if (!$DoctorID || !$Name) {
            ApiResponse::error('DoctorID and Name are required', 400);
        }
        
        $sql = "INSERT INTO DOCTOR (DoctorID, CenterID, Name, Specialization, Qualification, YearsOfExperience, OverallRating) 
                VALUES ('$DoctorID', '$CenterID', '$Name', '$Specialization', '$Qualification', $YearsOfExperience, $OverallRating)";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['DoctorID' => $DoctorID], 'Doctor created successfully', 201);
        } else {
            ApiResponse::error('Error creating doctor', 500);
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
