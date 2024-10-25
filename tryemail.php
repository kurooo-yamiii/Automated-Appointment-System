<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

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
    $mail->addAddress('kennethcobarubias12@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Rescheduled Appointment';
    $mail->Body    = 'Sample Mail';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
?>