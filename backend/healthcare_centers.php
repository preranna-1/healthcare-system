
<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        if (isset($request[2]) && !empty($request[2])) {
            // GET single center by ID
            $centerID = $conn->real_escape_string($request[2]);
            $sql = "SELECT * FROM HEALTHCARE_CENTER WHERE CenterID = '$centerID'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                $center = $result->fetch_assoc();
                ApiResponse::success($center, 'Healthcare center retrieved');
            } else {
                ApiResponse::error('Healthcare center not found', 404);
            }
        } else {
            // GET all centers
            $sql = "SELECT * FROM HEALTHCARE_CENTER";
            $result = $conn->query($sql);
            
            if ($result) {
                $centers = [];
                while ($row = $result->fetch_assoc()) {
                    $centers[] = $row;
                }
                ApiResponse::success($centers, 'Healthcare centers retrieved');
            } else {
                ApiResponse::error('Error fetching centers', 500);
            }
        }
    } 
    elseif ($method === 'POST') {
        // CREATE new center
        $data = json_decode(file_get_contents('php://input'), true);
        
        $CenterID = $data['CenterID'] ?? null;
        $Name = $data['Name'] ?? null;
        $Address = $data['Address'] ?? null;
        $PhoneNumbers = $data['PhoneNumbers'] ?? null;
        $Email = $data['Email'] ?? null;
        $Latitude = $data['Latitude'] ?? null;
        $Longitude = $data['Longitude'] ?? null;
        $OperatingHours = $data['OperatingHours'] ?? null;
        $OverallRating = $data['OverallRating'] ?? 0;
        $CenterType = $data['CenterType'] ?? null;
        
        if (!$CenterID || !$Name) {
            ApiResponse::error('CenterID and Name are required', 400);
        }
        
        $sql = "INSERT INTO HEALTHCARE_CENTER (CenterID, Name, Address, PhoneNumbers, Email, Latitude, Longitude, OperatingHours, OverallRating, CenterType) 
                VALUES ('$CenterID', '$Name', '$Address', '$PhoneNumbers', '$Email', $Latitude, $Longitude, '$OperatingHours', $OverallRating, '$CenterType')";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['CenterID' => $CenterID], 'Healthcare center created successfully', 201);
        } else {
            ApiResponse::error('Error creating center: ' . $conn->error, 500);
        }
    }
    elseif ($method === 'PUT') {
        // UPDATE center
        $centerID = $request[2] ?? null;
        if (!$centerID) {
            ApiResponse::error('Center ID is required', 400);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $updates = [];
        if (isset($data['Name'])) $updates[] = "Name = '{$data['Name']}'";
        if (isset($data['Address'])) $updates[] = "Address = '{$data['Address']}'";
        if (isset($data['PhoneNumbers'])) $updates[] = "PhoneNumbers = '{$data['PhoneNumbers']}'";
        if (isset($data['Email'])) $updates[] = "Email = '{$data['Email']}'";
        if (isset($data['Latitude'])) $updates[] = "Latitude = {$data['Latitude']}";
        if (isset($data['Longitude'])) $updates[] = "Longitude = {$data['Longitude']}";
        if (isset($data['OperatingHours'])) $updates[] = "OperatingHours = '{$data['OperatingHours']}'";
        if (isset($data['OverallRating'])) $updates[] = "OverallRating = {$data['OverallRating']}";
        if (isset($data['CenterType'])) $updates[] = "CenterType = '{$data['CenterType']}'";
        
        if (empty($updates)) {
            ApiResponse::error('No fields to update', 400);
        }
        
        $sql = "UPDATE HEALTHCARE_CENTER SET " . implode(', ', $updates) . " WHERE CenterID = '$centerID'";
        
        if ($conn->query($sql)) {
            ApiResponse::success([], 'Healthcare center updated successfully');
        } else {
            ApiResponse::error('Error updating center', 500);
        }
    }
    elseif ($method === 'DELETE') {
        // DELETE center
        $centerID = $request[2] ?? null;
        if (!$centerID) {
            ApiResponse::error('Center ID is required', 400);
        }
        
        $sql = "DELETE FROM HEALTHCARE_CENTER WHERE CenterID = '$centerID'";
        
        if ($conn->query($sql)) {
            ApiResponse::success([], 'Healthcare center deleted successfully');
        } else {
            ApiResponse::error('Error deleting center', 500);
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
