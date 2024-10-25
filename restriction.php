<?php 
session_start(); 
include "db_conn.php";

$date = $_GET['date'];

// Query to count the number of appointments for the given date
$sql = "SELECT COUNT(*) as count FROM student_appointment WHERE Date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$appointmentCount = $row['count'];

// Check if the date has 10 or more appointments
$isFull = ($appointmentCount >= 25);

// Query to get all restricted dates
$sql2 = "SELECT Date FROM restriction";
$result2 = mysqli_query($conn, $sql2);

$restrictedDates = [];
while ($row2 = mysqli_fetch_assoc($result2)) {
    $restrictedDates[] = $row2['Date'];
}

// Return the JSON response
echo json_encode([
    'appointmentCount' => $appointmentCount,
    'isFull' => $isFull,
    'restrictedDates' => $restrictedDates
]);
?>