<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        if (isset($request[2]) && $request[2] === 'available') {
            $sql = "SELECT * FROM Bed WHERE IsAvailable = TRUE";
        } else {
            $sql = "SELECT * FROM Bed";
        }
        
        $result = $conn->query($sql);
        $beds = [];
        while ($row = $result->fetch_assoc()) {
            $beds[] = $row;
        }
        ApiResponse::success($beds, 'Beds retrieved');
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $BedID = $data['BedID'] ?? null;
        $CenterID = $data['CenterID'] ?? null;
        $BedType = $data['BedType'] ?? null;
        $BedName = $data['BedName'] ?? null;
        $IsAvailable = $data['IsAvailable'] ?? true;
        
        if (!$BedID) {
            ApiResponse::error('BedID is required', 400);
        }
        
        $sql = "INSERT INTO Bed (BedID, CenterID, BedType, BedName, IsAvailable, LastUpdate) 
                VALUES ($BedID, '$CenterID', '$BedType', '$BedName', $IsAvailable, NOW())";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['BedID' => $BedID], 'Bed created successfully', 201);
        } else {
            ApiResponse::error('Error creating bed', 500);
        }
    }
    elseif ($method === 'PUT') {
        $bedID = $request[2] ?? null;
        if (!$bedID) {
            ApiResponse::error('Bed ID is required', 400);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $updates = ["LastUpdate = NOW()"];
        
        if (isset($data['IsAvailable'])) $updates[] = "IsAvailable = " . ($data['IsAvailable'] ? '1' : '0');
        if (isset($data['BedType'])) $updates[] = "BedType = '{$data['BedType']}'";
        
        $sql = "UPDATE Bed SET " . implode(', ', $updates) . " WHERE BedID = $bedID";
        
        if ($conn->query($sql)) {
            ApiResponse::success([], 'Bed updated successfully');
        } else {
            ApiResponse::error('Error updating bed', 500);
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
