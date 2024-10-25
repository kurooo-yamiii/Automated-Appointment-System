<?php 
session_start(); 
include "db_conn.php";

$date = $_GET['date'];

// Define all possible appointment times in the correct order
$allTimes = [
    "8:00 AM", "8:15 AM", "8:30 AM", "8:45 AM", "9:00 AM", "9:15 AM", "9:30 AM", "9:45 AM",
    "10:00 AM", "10:15 AM", "10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", 
    "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", 
    "2:45 PM", "3:00 PM"
];

// Fetch appointments for the given date
$sql = "SELECT * FROM `overall_appointments` WHERE Date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $appointments[] = [
        'id' => $row['ID'], // Assuming the appointment ID is in the 'ID' column
        'time' => $row['Time'],
        'office' => $row['Office'],
        'name' => $row['Name'],
        'branch' => $row['Branch'] // Assuming the appointment time is in the 'Time' column
    ];
}

// Create an associative array to store appointments by time
$appointmentsByTime = array_fill_keys($allTimes, []);

// Assign appointments to the correct time slot
foreach ($appointments as $appointment) {
    $time = date("g:i A", strtotime($appointment['time'])); // Ensure time format matches $allTimes
    if (array_key_exists($time, $appointmentsByTime)) {
        $appointmentsByTime[$time][] = $appointment;
    }
}

// Flatten the array while preserving the order of times
$orderedAppointments = [];
foreach ($allTimes as $time) {
    if (!empty($appointmentsByTime[$time])) {
        $orderedAppointments = array_merge($orderedAppointments, $appointmentsByTime[$time]);
    }
}

// Return the JSON response
echo json_encode([
    'appointment' => $orderedAppointments
]);
?>
