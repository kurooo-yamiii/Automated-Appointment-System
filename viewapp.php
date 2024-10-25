<?php 

include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	
    $email = $_POST['email'];
  
    // Checking Cooperating Teacher
    $check = "SELECT * FROM overall_appointments WHERE Email = '$email'";
    $checkres = mysqli_query($conn, $check);
    $appointment = mysqli_fetch_assoc($checkres);


    if($email == null){
        echo '<p class="error" style="margin-top: 3px;">Please type your email first in the textbox</p>';
        echo '<div class="choice-container">
        
        <button class="choice-button" type="button" onclick="hideViewpop()">Back</button>
      </div>';
    }
    else if ($appointment) {
        // Echo the input fields with the fetched data
        echo '<div style="width: 100%;"><label for="name">Name:</label>';
        echo '<input type="text" id="name" name="Name" placeholder="Name" value="' . htmlspecialchars($appointment['Name']) . '" readonly required></div>';
    
        echo '<div style="width: 100%;"><label for="date">Date:</label>';
        echo '<input type="text" id="date" name="Date" placeholder="Date" value="' . htmlspecialchars($appointment['Date']) . '" readonly required></div>';
    
        echo '<div style="width: 100%;"><label for="time">Time:</label>';
        echo '<input type="text" id="time" name="Time" placeholder="Time" value="' . htmlspecialchars($appointment['Time']) . '" readonly required></div>';
     
        echo '<div style="width: 100%;"><label for="branch">Branch:</label>';
        echo '<input type="text" id="branch" name="Branch" placeholder="Branch" value="' . htmlspecialchars($appointment['Branch']) . '" readonly required></div>';
    
        echo '<div style="width: 100%;"><label for="office">Office:</label>';
        echo '<input type="text" id="office" name="office" placeholder="Office" value="' . htmlspecialchars($appointment['Office']) . '" readonly required></div>';

        echo '<div class="choice-container">
        <button class="choice-button" type="button" onclick="printAppPDF()">Download PDF</button>
        <button class="choice-button" type="button" onclick="hideViewpop()">Back</button>
      </div>';
    } else {

        echo '<p class="error" style="margin-top: 3px;">No appointment found for the provided email</p>';
        echo '<div class="choice-container">
        
        <button class="choice-button" type="button" onclick="hideViewpop()">Back</button>
      </div>';
    }
}else{
    header("Location: index.php?error=Please insert your Email First");
	exit();
}