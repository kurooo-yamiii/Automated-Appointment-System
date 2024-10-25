<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
include "db_conn.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Initialize the sent_emails session variable if not already set
if (!isset($_SESSION['sent_emails'])) {
    $_SESSION['sent_emails'] = [];
}

if (isset($data['date'])) {
    $date = $data['date'];
   
    $stmt = $conn->prepare("SELECT Email FROM overall_appointments WHERE Date = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit();
    }
    
    $stmt->bind_param("s", $date);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row['Email'];
        }
        
        if (!empty($appointments)) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'servicestechflow@gmail.com';
                $mail->Password = 'aggpsqzakykmfqxc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Sender info
                $mail->setFrom('servicestechflow@gmail.com', 'Techflow Guidelines and Services');

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Reminder';
                $mail->Body = '
                <p>Good Day!</p>
                <p>I hope this message finds you well. This is a friendly reminder about your upcoming appointment scheduled for tomorrow.</p>
                <p>Please ensure that you bring all the following items that are needed for your appointment:</p>
                <ul>
                    <li>Documents Needed for your Appointment</li>
                    <li>Appointment Letter</li>
                    <li>Authorization Letter (For Authorize Person)</li>
                </ul>
                <p>If you have any questions or need to reschedule, please do not hesitate to contact us at <a href="mailto:servicestechflow@gmail.com">servicestechflow@gmail.com</a>.</p>
                <p>Thank you and have a great day!</p>
                <p>Sincerely,<br>
                Techflow Guidelines and Services</p>
            ';

                // Send to each email
                foreach ($appointments as $email) {
                    if (!in_array($email, $_SESSION['sent_emails'])) {
                        $mail->addAddress($email);
                        $mail->send();
                        $mail->clearAddresses(); // Clear addresses for the next iteration
                        $_SESSION['sent_emails'][] = $email; // Track the sent email
                    }
                }
                
                echo json_encode(['success' => true, 'message' => 'Reminder emails sent.']);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No appointments for tomorrow.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Date not provided']);
}
?>
