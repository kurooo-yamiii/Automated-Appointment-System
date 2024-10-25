<?php
session_start();
include "db_conn.php";

$date = $_GET['date'];
$dateLike = $date . '%'; 

// Query to get all the appointments for the given date
$sql = "SELECT Time FROM overall_appointments WHERE Date LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$takenTimes = [];
while ($row = $result->fetch_assoc()) {
    $takenTimes[] = $row['Time'];
}

// List of all possible times
$allTimes = [
    "8:00 AM", "8:15 AM", "8:30 AM", "8:45 AM", "9:00 AM", "9:15 AM", "9:30 AM", "9:45 AM",
    "10:00 AM", "10:15 AM", "10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", 
    "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", 
    "2:45 PM", "3:00 PM"
];

// Calculate available times
$availableTimes = array_diff($allTimes, $takenTimes);
if($availableTimes == null){
    echo json_encode([
        'availableTimes' => 'Null'
    ]);
}
// Return available times as JSON
echo json_encode([
    'availableTimes' => array_values($availableTimes)
]);
?>