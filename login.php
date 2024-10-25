<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);


	if (empty($uname)) {
		header("Location: enter.php?error=Username is required");
	    exit();
	}else if(empty($pass)){
        header("Location: enter.php?error=Password is required");
	    exit();
	}else{

		
		$sql = "SELECT * FROM user WHERE Username='$uname'";
		$result = mysqli_query($conn, $sql);
		
	 
		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['Username'] === $uname && $row['Password'] === $pass) {
            	
				$_SESSION['Username'] = $row['Username'];
            	$_SESSION['Name'] = $row['Name'];
            	$_SESSION['Password'] = $row['Password'];
				$_SESSION['ID'] = $row['ID'];
            	header("Location: administrator.php");
		        exit();}

        
			else{
					header("Location: enter.php?error=Incorrect Username or Password");
					exit();
				}
            }else{
				header("Location: enter.php?error=Incorrect Username or Password");
							exit();
			}
	}
	
}else{
	header("Location: enter.php");
	exit();
}