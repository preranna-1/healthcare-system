<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM PATIENT";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $PatientID = $input['PatientID'];
    $UserID = $input['UserID'];
    $DOB = $input['DOB'];
    $Gender = $input['Gender'];
    $BloodGroup = $input['BloodGroup'];
    $DiseaseType = $input['DiseaseType'];

    $stmt = $conn->prepare("INSERT INTO PATIENT (PatientID, UserID, DOB, Gender, BloodGroup, DiseaseType) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $PatientID, $UserID, $DOB, $Gender, $BloodGroup, $DiseaseType);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Patient added successfully"]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }
}
?>
