<?php
session_start();
include "db_conn.php";
include 'db_pdo.php';

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

    $title = validate($_POST['title']);
    $desc = validate($_POST['desc']);
    $author = validate($_POST['author']);
    $date = validate($_POST['date']);
    $dropAppointment = isset($_POST['drop_appointment']) && $_POST['drop_appointment'] === 'true';

    $newdate = new DateTime($date);  // Ensure correct variable name
    $formattedDate = $newdate->format('F j, Y');

    if ($dropAppointment) {
        $fetchapp = "SELECT Email FROM overall_appointments WHERE Date = ?";
        $stmt = $conn->prepare($fetchapp);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalapp = $result->num_rows;

        $emailArray = array();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $emailArray[] = $row['Email'];
            }

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

                // Add all email addresses from the array
                foreach ($emailArray as $email) {
                    $mail->addAddress($email);
                }

                $mail->isHTML(true);
                $mail->Subject = 'Rescheduled Appointment';
                $mail->Body    = '
                <html>
                <head>
                  <style>
                    .content {
                      font-family: Arial, sans-serif;
                      line-height: 1.6;
                    }
                  </style>
                </head>
                <body>
                  <div class="content">
                    <p>Good day!</p>
                    <p>I like to inform you that, due to certain unforeseen circumstances, we regretfully have to cancel the appointment that was previously scheduled.</p>
                    <p>We understand that this may cause some inconvenience and sincerely apologize for any disruption this may cause to your schedule.</p>
                    <p>It is essential to reschedule this appointment to ensure that your needs are adequately met. We kindly request you visit our website and set another schedule that will fit your time.</p>
                    <p>Thank you for your understanding and cooperation. We deeply regret any inconvenience caused and appreciate your flexibility in this matter.</p>
                    <p>Yours sincerely,</p>
                    <p>Techflow Guidelines and Services</p>
                  </div>
                </body>
                </html>';
                $mail->send();

            } catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            }

            $createAnnounce = $conn->prepare("INSERT INTO announcement (Title, Description, Author, Date) VALUES (?, ?, ?, ?)");
            $createAnnounce->bind_param("ssss", $title, $desc, $author, $formattedDate);
            $exeCreateAnnounce = $createAnnounce->execute();

            $createRestriction = $conn->prepare("INSERT INTO restriction (Date) VALUES (?)");
            $createRestriction->bind_param("s", $date);
            $exeCreateRestriction = $createRestriction->execute();

			$delAppoint = $conn->prepare("DELETE FROM overall_appointments WHERE Date = ?");
            $delAppoint->bind_param("s", $date);
            $delAppexe = $delAppoint->execute();

            if ($exeCreateRestriction && $exeCreateAnnounce && $delAppexe) {
                echo '<p class="success">Total of ' . $totalapp . ' has been successfully dropped and notified</p>';

                $announcement = $ponn->query("SELECT * FROM announcement ORDER BY ID DESC");

                if ($announcement->rowCount() <= 0) {
                    echo '<div class="todo-item">';
                    echo '    <a href="" id="remove-to-do" class="removee-to-do">N/A</a> <br>';
                    echo '    <div class="row-announce">';
                    echo '        <div class="empty">';
                    echo '            <img src="resources/default.jpeg">';
                    echo '        </div>';
                    echo '        <div class="post-ann">';
                    echo '            <h2>There is no announcement currently</h2>';
                    echo '            <small>Note: If you want to add announcement click the button "create announcement" in the upper right corner</small>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                } else {
                    while ($fetchannounce = $announcement->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="todo-item">';
                        echo '    <button onclick="DeleteAnnouncement(' . $fetchannounce['ID'] . ')" id="remove-to-do" class="removee-to-do">' . $fetchannounce['Date'] . '</button> <br>';
                        echo '    <div class="row-announce">';
                        echo '        <div class="empty">';
                        echo '            <img src="resources/cedlogo.png">';
                        echo '        </div>';
                        echo '        <div class="post-ann">';
                        echo '            <h2 style="font-weight: 700; font-size: 18px;">' . $fetchannounce['Title'] . '</h2>';
                        echo '            <h2>' . $fetchannounce['Description'] . '</h2>';
                        echo '            <small>- ' . $fetchannounce['Author'] . '</small>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                }
            }
        }
    } else {
        $createAnnounce = $conn->prepare("INSERT INTO announcement (Title, Description, Author, Date) VALUES (?, ?, ?, ?)");
        $createAnnounce->bind_param("ssss", $title, $desc, $author, $formattedDate);
        $exeCreateAnnounce = $createAnnounce->execute();

        $createRestriction = $conn->prepare("INSERT INTO restriction (Date) VALUES (?)");
        $createRestriction->bind_param("s", $date);
        $exeCreateRestriction = $createRestriction->execute();

        if ($exeCreateRestriction && $exeCreateAnnounce) {
            echo '<p class="success">Announcement successfully posted</p>';

            $announcement = $ponn->query("SELECT * FROM announcement ORDER BY ID DESC");

            if ($announcement->rowCount() <= 0) {
                echo '<div class="todo-item">';
                echo '    <a href="" id="remove-to-do" class="removee-to-do">N/A</a> <br>';
                echo '    <div class="row-announce">';
                echo '        <div class="empty">';
                echo '            <img src="resources/default.jpeg">';
                echo '        </div>';
                echo '        <div class="post-ann">';
                echo '            <h2>There is no announcement currently</h2>';
                echo '            <small>Note: If you want to add announcement click the button "create announcement" in the upper right corner</small>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            } else {
                while ($fetchannounce = $announcement->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="todo-item">';
                    echo '    <button onclick="DeleteAnnouncement(' . $fetchannounce['ID'] . ')" id="remove-to-do" class="removee-to-do">' . $fetchannounce['Date'] . '</button> <br>';
                    echo '    <div class="row-announce">';
                    echo '        <div class="empty">';
                    echo '            <img src="resources/cedlogo.png">';
                    echo '        </div>';
                    echo '        <div class="post-ann">';
                    echo '            <h2 style="font-weight: 700; font-size: 18px;">' . $fetchannounce['Title'] . '</h2>';
                    echo '            <h2>' . $fetchannounce['Description'] . '</h2>';
                    echo '            <small>- ' . $fetchannounce['Author'] . '</small>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
            }
        }
    }
}
?>
