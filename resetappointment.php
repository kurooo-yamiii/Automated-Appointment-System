<?php
session_start();
include "db_conn.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['date'])) {
    $date = $data['date'];
    
    
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => $conn->connect_error]);
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM overall_appointments WHERE Date = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $date);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'deleted' => $stmt->affected_rows]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Date not provided']);
}
?>
