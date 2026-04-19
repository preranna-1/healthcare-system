<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        if (isset($request[2]) && $request[2] === 'available') {
            $sql = "SELECT * FROM AMBULANCE WHERE IsAvailable = TRUE";
        } else if (isset($request[2]) && !empty($request[2])) {
            $ambulanceID = $conn->real_escape_string($request[2]);
            $sql = "SELECT * FROM AMBULANCE WHERE AmbulanceID = '$ambulanceID'";
        } else {
            $sql = "SELECT * FROM AMBULANCE";
        }
        
        $result = $conn->query($sql);
        $ambulances = [];
        while ($row = $result->fetch_assoc()) {
            $ambulances[] = $row;
        }
        ApiResponse::success($ambulances, 'Ambulances retrieved');
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $AmbulanceID = $data['AmbulanceID'] ?? null;
        $CenterID = $data['CenterID'] ?? null;
        $RegistrationNumber = $data['RegistrationNumber'] ?? null;
        $DriverName = $data['DriverName'] ?? null;
        $DriverPhone = $data['DriverPhone'] ?? null;
        $Type = $data['Type'] ?? null;
        $IsAvailable = $data['IsAvailable'] ?? true;
        $CurrentLatitude = $data['CurrentLatitude'] ?? null;
        $CurrentLongitude = $data['CurrentLongitude'] ?? null;
        
        if (!$AmbulanceID) {
            ApiResponse::error('AmbulanceID is required', 400);
        }
        
        $sql = "INSERT INTO AMBULANCE (AmbulanceID, CenterID, RegistrationNumber, DriverName, DriverPhone, Type, IsAvailable, CurrentLatitude, CurrentLongitude) 
                VALUES ('$AmbulanceID', '$CenterID', '$RegistrationNumber', '$DriverName', '$DriverPhone', '$Type', $IsAvailable, $CurrentLatitude, $CurrentLongitude)";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['AmbulanceID' => $AmbulanceID], 'Ambulance created successfully', 201);
        } else {
            ApiResponse::error('Error creating ambulance', 500);
        }
    }
    elseif ($method === 'PUT') {
        $ambulanceID = $request[2] ?? null;
        if (!$ambulanceID) {
            ApiResponse::error('Ambulance ID is required', 400);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $updates = [];
        
        if (isset($data['IsAvailable'])) $updates[] = "IsAvailable = " . ($data['IsAvailable'] ? '1' : '0');
        if (isset($data['CurrentLatitude'])) $updates[] = "CurrentLatitude = {$data['CurrentLatitude']}";
        if (isset($data['CurrentLongitude'])) $updates[] = "CurrentLongitude = {$data['CurrentLongitude']}";
        
        if (empty($updates)) {
            ApiResponse::error('No fields to update', 400);
        }
        
        $sql = "UPDATE AMBULANCE SET " . implode(', ', $updates) . " WHERE AmbulanceID = '$ambulanceID'";
        
        if ($conn->query($sql)) {
            ApiResponse::success([], 'Ambulance updated successfully');
        } else {
            ApiResponse::error('Error updating ambulance', 500);
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
