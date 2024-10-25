<?php
session_start();
include "db_conn.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $limit = validate($_POST['limit']);
    $id = validate($_POST['id']);
    $email = validate($_POST['email']);
    $name = validate($_POST['name']);
    $office = validate($_POST['office']);
    $branch = validate($_POST['branch']);
    $date = validate($_POST['date']);
    $time = validate($_POST['time']);

    $formdate = new DateTime($date);  
    $olddate = $formdate->format('F j, Y');

    $checkinfo = "SELECT * FROM overall_appointments WHERE ID = '$id'";
    $checkinfpexe = mysqli_query($conn, $checkinfo);
    $checkresult = mysqli_fetch_assoc($checkinfpexe);

    $newd = $checkresult['Date'];
    $formdate2 = new DateTime($newd);  
    $newdate = $formdate2->format('F j, Y');

    if($date != $checkresult['Date'] || $time != $checkresult['Time']){

        $updatedate = "UPDATE overall_appointments SET Name = '$name', Office = '$office', Branch = '$branch', Date = '$date', Time = '$time' WHERE ID = '$id'";
        $executeup = mysqli_query($conn, $updatedate);

        if($executeup){
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'servicestechflow@gmail.com';
                $mail->Password = 'aggpsqzakykmfqxc';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
    
                $mail->setFrom('servicestechflow@gmail.com', 'Techflow Guidelines and Services');
                $mail->addAddress($email);
    
                $mail->isHTML(true);
                $mail->Subject = 'Update Appointment';
                $mail->Body    = '
                <p>Good Day,</p>
                <p>We regret to inform you that due to certain unforeseen circumstances, we must reschedule your upcoming appointment.</p>
                <p><strong>Original Appointment Details:</strong><br>
                Date: ' . htmlspecialchars($newdate) . '<br>
                Time: ' . htmlspecialchars($checkresult['Time']) . '</p>
                <p><strong>New Appointment Details:</strong><br>
                Date: ' . htmlspecialchars($olddate) . '<br>
                Time: ' . htmlspecialchars($time) . '</p>
                <p>Your schedule is important to us. Our team is committed to providing you with the best possible service, and we believe this rescheduling will ensure that your needs are adequately met.</p>
                <p>Please confirm your availability for the new appointment date and time by responding to this email or contacting our office at <a href="mailto:servicestechflow@gmail.com">servicestechflow@gmail.com</a>. If the new schedule does not suit you, kindly let us know your preferred date and time, and we will do our best to accommodate your request.</p>
                <p>Thank you for your understanding and cooperation.</p>
                <p>Sorry for the inconvenience.</p>
                <p>Yours sincerely,<br>
                Techflow Guidelines and Services</p>';
    
                $mail->send();
              
            } catch (Exception $e) {
               
            }

            echo '<p class="success" style="margin-bottom: -2%; margin-top: 2%;">Appointee has been notified, Appointment Successfully Updated</p>';

            $sqlsearch = "SELECT * FROM overall_appointments ORDER BY ID DESC LIMIT $limit";
            $resultsearch = mysqli_query($conn, $sqlsearch);

            echo '<table class="table table-striped" id="startupproftable">';
            echo '  <thead>';
            echo '    <tr>';
            echo '      <th scope="col">Name</th>';
            echo '      <th scope="col">Date</th>';
            echo '      <th scope="col">Time</th>';
            echo '      <th scope="col">Office</th>';
            echo '      <th scope="col">Branch</th>';
            echo '      <th scope="col">Action</th>';
            echo '    </tr>';
            echo '  </thead>';
            echo '  <tbody>';

            if (mysqli_num_rows($resultsearch) > 0) {
                while ($rows = mysqli_fetch_assoc($resultsearch)) {
                    echo '    <tr>';
                    echo '      <td>' . htmlspecialchars($rows['Name']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Date']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Time']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Office']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Branch']) . '</td>';
                    echo '      <td>';
                    echo '        <button type="button" onclick="deleteAppoint(' . (int)$rows['ID'] . ', \'' . addslashes($rows['Email']) . '\');" class="red-button">Delete</button>';
                    echo '        <button type="button" onclick="updateAppoint(' . (int)$rows['ID'] . ',\'' . htmlspecialchars($rows['Email'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Name'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Date'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Time'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Office'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Branch'], ENT_QUOTES) . '\');" class="blue-button">Update</button>';
                    echo '      </td>';
                    echo '    </tr>';
                }
            } else {
                echo '    <tr>';
                echo '      <td colspan="6">No records found</td>';
                echo '    </tr>';
            }

            echo '  </tbody>';
            echo '</table>';
        }
    } else {
        $updatedate = "UPDATE overall_appointments SET Name = '$name', Office = '$office', Branch = '$branch', Date = '$date', Time = '$time' WHERE ID = '$id'";
        $executeup = mysqli_query($conn, $updatedate);

        if($executeup){
            echo '<p class="success" style="margin-bottom: -2%;">Appointee has been notified, Appointment Successfully Deleted</p>';

            $sqlsearch = "SELECT * FROM overall_appointments ORDER BY ID DESC LIMIT $limit";
            $resultsearch = mysqli_query($conn, $sqlsearch);

            echo '<table class="table table-striped" id="startupproftable">';
            echo '  <thead>';
            echo '    <tr>';
            echo '      <th scope="col">Name</th>';
            echo '      <th scope="col">Date</th>';
            echo '      <th scope="col">Time</th>';
            echo '      <th scope="col">Office</th>';
            echo '      <th scope="col">Branch</th>';
            echo '      <th scope="col">Action</th>';
            echo '    </tr>';
            echo '  </thead>';
            echo '  <tbody>';

            if (mysqli_num_rows($resultsearch) > 0) {
                while ($rows = mysqli_fetch_assoc($resultsearch)) {
                    echo '    <tr>';
                    echo '      <td>' . htmlspecialchars($rows['Name']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Date']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Time']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Office']) . '</td>';
                    echo '      <td>' . htmlspecialchars($rows['Branch']) . '</td>';
                    echo '      <td>';
                    echo '        <button type="button" onclick="deleteAppoint(' . (int)$rows['ID'] . ', \'' . addslashes($rows['Email']) . '\');" class="red-button">Delete</button>';
                    echo '        <button type="button" onclick="updateAppoint(' . (int)$rows['ID'] . ',\'' . htmlspecialchars($rows['Email'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Name'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Date'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Time'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Office'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Branch'], ENT_QUOTES) . '\');" class="blue-button">Update</button>';
                    echo '      </td>';
                    echo '    </tr>';
                }
            } else {
                echo '    <tr>';
                echo '      <td colspan="6">No records found</td>';
                echo '    </tr>';
            }

            echo '  </tbody>';
            echo '</table>';
        }
    }
}
?>
