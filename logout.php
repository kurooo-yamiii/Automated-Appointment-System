<?php
session_start(); 

// Check if the user is logged in
if(isset($_SESSION['Username'])) {

    $_SESSION = array();

    
    session_destroy();
}

header("Location: enter.php");
exit();
?>