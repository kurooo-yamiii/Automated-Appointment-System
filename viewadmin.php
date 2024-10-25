<?php 
include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
  
    // Checking Cooperating Teacher
    $check = "SELECT * FROM overall_appointments WHERE ID = '$id'";
    $checkres = mysqli_query($conn, $check);
    $appointment = mysqli_fetch_assoc($checkres);
    $email = $appointment['Email'];

    if ($appointment['Type'] == "Student") {
    
        $student = "SELECT * FROM student_appointment WHERE Email = '$email'";
        $checkstudent = mysqli_query($conn, $student);
        $studentrow = mysqli_fetch_assoc($checkstudent);

        if($studentrow){
            echo '<div style="width: 100%; margin-top: 3%;"><input type="text" id="" name="" value="&#9679; Name: ' . htmlspecialchars($studentrow['Name']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Student Number: ' . htmlspecialchars($studentrow['Student']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Date: ' . htmlspecialchars($studentrow['Date']) . ' on ' . htmlspecialchars($appointment['Time']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Contact Number: ' . htmlspecialchars($studentrow['Contact']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Email: ' . htmlspecialchars($studentrow['Email']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Branch: ' . htmlspecialchars($studentrow['Branch']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Office: ' . htmlspecialchars($studentrow['Office']) . '" readonly></div>';
            echo '<div style="width: 100%;"><textarea id="" name="" readonly>&#9679; Purpose: ' . htmlspecialchars($studentrow['Purpose']) . '</textarea></div>';
            echo '<div class="choice-container"><button style="background-color: #003f66;" class="choice-button" type="button" onclick="hideViewpop()">Back</button></div>';
        }
    } else if($appointment['Type'] == "Employee") {

        $employee = "SELECT * FROM employee_appointment WHERE Email = '$email'";
        $checkemployee = mysqli_query($conn, $employee);
        $employeerow = mysqli_fetch_assoc($checkemployee);
        
        if($employeerow){
            echo '<div style="width: 100%; margin-top: 3%;"><input type="text" id="" name="" value="&#9679; Name: ' . htmlspecialchars($employeerow['Name']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Employee Number: ' . htmlspecialchars($employeerow['Employee']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Date: ' . htmlspecialchars($employeerow['Date']) . ' on ' . htmlspecialchars($appointment['Time']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Contact Number: ' . htmlspecialchars($employeerow['Contact']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Email: ' . htmlspecialchars($employeerow['Email']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Branch: ' . htmlspecialchars($employeerow['Branch']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Office: ' . htmlspecialchars($employeerow['Office']) . '" readonly></div>';
            echo '<div style="width: 100%;"><textarea id="" name="" readonly>&#9679; Purpose: ' . htmlspecialchars($employeerow['Purpose']) . '</textarea></div>';
            echo '<div class="choice-container"><button style="background-color: #003f66;" class="choice-button" type="button" onclick="hideViewpop()">Back</button></div>';
        }
      
    } else {

        $guest = "SELECT * FROM guest_appointment WHERE Email = '$email'";
        $checkguest = mysqli_query($conn, $guest);
        $guestrow = mysqli_fetch_assoc($checkguest);

        if($guestrow){
            echo '<div style="width: 100%; margin-top: 3%;"><input type="text" id="" name="" value="&#9679; Name: ' . htmlspecialchars($guestrow['Name']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; ID: ' . htmlspecialchars($guestrow['Type']) . ' - ' . htmlspecialchars($guestrow['Identity']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Date: ' . htmlspecialchars($guestrow['Date']) . ' on ' . htmlspecialchars($appointment['Time']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Contact Number: ' . htmlspecialchars($guestrow['Contact']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Email: ' . htmlspecialchars($guestrow['Email']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Branch: ' . htmlspecialchars($guestrow['Branch']) . '" readonly></div>';
            echo '<div style="width: 100%;"><input type="text" id="" name="" value="&#9679; Office: ' . htmlspecialchars($guestrow['Office']) . '" readonly></div>';
            echo '<div style="width: 100%;"><textarea id="" name="" readonly>&#9679; Purpose: ' . htmlspecialchars($guestrow['Purpose']) . '</textarea></div>';
            echo '<div class="choice-container"><button style="background-color: #003f66;" class="choice-button" type="button" onclick="hideViewpop()">Back</button></div>';
        }
    }
}
