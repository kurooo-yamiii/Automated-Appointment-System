<?php
// Getting Connection to the published Database
$sname= "localhost";
$uname= "root";
$password = "";

$db_name = "appointment";


try {
    $ponn = new PDO("mysql:host=$sname;dbname=$db_name", 
                    $uname, $password);
    $ponn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}