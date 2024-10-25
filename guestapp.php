<?php 
session_start(); 
include "db_conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}


	$branch = validate($_POST['branch']);
	$office = validate($_POST['office']);
    $email = validate($_POST['email']);
    $contact = validate($_POST['contact']);
    $first = validate($_POST['first']);
    $last = validate($_POST['last']);
    $type = validate($_POST['type']);
    $identification = validate($_POST['identity']);
    $purpose = validate($_POST['purpose']);
    $date = validate($_POST['date']);
    $time = validate($_POST['time']);

    $name = $first . " " . $last;

    $sql = "SELECT * FROM overall_appointments WHERE Email LIKE '%$email%'";
    $result = mysqli_query($conn, $sql);
    $totalresult = mysqli_num_rows($result);

    $timeexist = "SELECT * FROM overall_appointments WHERE Date = '$date' AND Time = '$time'";
    $checkexist = mysqli_query($conn, $timeexist);
    $timeresult = mysqli_num_rows($checkexist);

    $limit = "SELECT * FROM overall_appointments WHERE Date='$date'";
    $limitres = mysqli_query($conn, $limit);
    $totalRows = mysqli_num_rows($limitres);


    if($totalresult > 0){
        http_response_code(500);
       
    }elseif($timeresult > 0){
        http_response_code(500);
   
    }elseif($totalRows > 25){
        http_response_code(500);
     
    }else{
        $insertstud = "INSERT INTO guest_appointment( Name, Branch, Office, Type, Identity, Contact, Email, Purpose, Date, Time ) 
        VALUES('$name', '$branch','$office','$type','$identification','$contact','$email','$purpose','$date','$time')";
        $insertstudres = mysqli_query($conn, $insertstud);

        $insertoverall = "INSERT INTO overall_appointments( Name, Date, Time, Office, Branch, Email, Type) 
        VALUES('$name', '$date','$time','$office','$branch','$email','Guest')";
        $insertoverallres = mysqli_query($conn, $insertoverall);

    }

    if($insertstudres && $insertoverallres){
   
       
       
    }else{
        http_response_code(500);
       
    }

}