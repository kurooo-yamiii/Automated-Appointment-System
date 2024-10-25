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

	$uname = validate($_POST['name']);
    $name = validate($_POST['username']);
	$pass = validate($_POST['password']);
    $confirm = validate($_POST['confirm']);
    $limit = validate($_POST['limit']);

    $sql = "SELECT * FROM user WHERE Username='$uname'";
    $result = mysqli_query($conn, $sql);

    if($result === 1){
        echo '<p class="error" style="margin-bottom: -2%;margin-top: 2%;">Email used is already taken</p>';
    }
    elseif($pass != $confirm){
        echo '<p class="error" style="margin-bottom: -2%; margin-top: 2%;">Password and confirm password does not match, Failed to create account</p>';
    }
    else{

        $check = "INSERT INTO user( Username, Password, Name, Avatar) 
        VALUES('$name', '$pass','$uname','N/A')";
        $checkres = mysqli_query($conn, $check);
        echo '<p class="success" style="margin-bottom: -2%; margin-top: 2%;">Account successfully created</p>';
    }

    $sqlsearch = "SELECT * FROM user ORDER BY ID DESC LIMIT $limit";
$resultsearch = mysqli_query($conn, $sqlsearch);

// Display the record in Table
if (mysqli_num_rows($resultsearch)) {
    echo '<table class="table table-striped" id="startupproftable">';
    echo '  <thead>';
    echo '    <tr>';
    echo '      <th scope="col">Name</th>';
    echo '      <th scope="col">Username</th>';
    echo '      <th scope="col">Password</th>';
    echo '      <th scope="col">Action</th>';
    echo '    </tr>';
    echo '  </thead>';
    echo '  <tbody>';

    $i = 0;
    while($rows = mysqli_fetch_assoc($resultsearch)) {
        $i++;
        echo '    <tr>';
        echo '      <td>' . htmlspecialchars($rows['Name']) . '</td>';
        echo '      <td>' . htmlspecialchars($rows['Username']) . '</td>';
        echo '      <td>' . htmlspecialchars($rows['Password']) . '</td>';
        echo '      <td><button type="button" onclick="DeleteUserShow(' . (int)$rows['ID'] . ');" class="red-button">Delete</button>';
        echo '        <button type="button" onclick="UpdateUserShow(' . (int)$rows['ID'] . ', \'' . htmlspecialchars($rows['Name'], ENT_QUOTES) . '\', \'' . htmlspecialchars($rows['Username'], ENT_QUOTES) . '\', \'' . htmlspecialchars($rows['Password'], ENT_QUOTES) . '\');" class="blue-button">Update</button>';
        echo '      </td>';
        echo '    </tr>';
    }

    echo '  </tbody>';
    echo '</table>';
} else {
    echo '    <tr>';
                        echo '      <td colspan="4">No records found</td>';
                        echo '    </tr>';
}

}