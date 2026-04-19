<?php
require_once '../db_connect.php';
require_once '../api_response.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

try {
    if ($method === 'GET') {
        $sql = "SELECT * FROM Feedback ORDER BY SubmittedAt DESC";
        $result = $conn->query($sql);
        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }
        ApiResponse::success($feedbacks, 'Feedback retrieved');
    }
    elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $FeedbackID = $data['FeedbackID'] ?? null;
        $PatientID = $data['PatientID'] ?? null;
        $CenterID = $data['CenterID'] ?? null;
        $DoctorID = $data['DoctorID'] ?? null;
        $HospitalRating = $data['HospitalRating'] ?? null;
        $DoctorRating = $data['DoctorRating'] ?? null;
        $EquipmentRating = $data['EquipmentRating'] ?? null;
        $Comments = $data['Comments'] ?? '';
        
        if (!$FeedbackID) {
            ApiResponse::error('FeedbackID is required', 400);
        }
        
        $Comments = $conn->real_escape_string($Comments);
        
        $sql = "INSERT INTO Feedback (FeedbackID, PatientID, CenterID, DoctorID, HospitalRating, DoctorRating, EquipmentRating, SubmittedAt, Comments) 
                VALUES ('$FeedbackID', '$PatientID', '$CenterID', '$DoctorID', $HospitalRating, $DoctorRating, $EquipmentRating, NOW(), '$Comments')";
        
        if ($conn->query($sql)) {
            ApiResponse::success(['FeedbackID' => $FeedbackID], 'Feedback submitted successfully', 201);
        } else {
            ApiResponse::error('Error submitting feedback', 500);
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
