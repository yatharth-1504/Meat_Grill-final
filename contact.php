<?php

/*
 * Script for sending E-Mail messages.
 * 
 * Note: Please edit $sendFrom variable value to your email address.
 * 
 */

// please change this to your E-Mail address
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$config = parse_ini_file('php.ini', true);

$sendFrom = $config['smtp']['from_email'];
$sendFromName = $config['smtp']['from_name'];
$smtpHost = $config['smtp']['host'];
$smtpUsername = $config['smtp']['username'];
$smtpPassword = $config['smtp']['password'];
$smtpPort = $config['smtp']['port'];
$bussiness_email = $config['smtp']['email'];

$action = $_POST['action'];
if ($action == 'contact') {
    $name = strip_tags($_POST['form_data'][0]['Name']);
    $email = strip_tags($_POST['form_data'][0]['Email']);
    $phone = isset($_POST['form_data'][0]['Phone']) ? strip_tags($_POST['form_data'][0]['Phone']) : '';
    $message = strip_tags($_POST['form_data'][0]['Message']);
    
    if(!empty($phone)){
        $message .= "\r\n" . "Phone: " . $phone;
    }
    
    // you can change default subject for Contact E-Mail here
    $subject = 'E-Mail from: ' . $name;

    if ($name == "" || $email == "" || $message == "") {
        echo "There was problem while sending E-Mail. Please verify entered data and try again!";
        exit();
    }
} else if ($action == 'newsletter') {
    $email = strip_tags($_POST['form_data'][0]['Email']);
    $name = $email;

    if ($email == "") {
        echo "There was problem while sending E-Mail. Please verify entered data and try again!";
        exit();
    }
    
    // you can change default subject for Newsletter E-Mail here
    $subject = 'Newsletter Subscribe!';
    
    $message = 'Newsletter Subscribe for User: ' . $email;
} else if ($action == 'comment') {
    $name = strip_tags($_POST['form_data'][0]['Name']);
    $email = strip_tags($_POST['form_data'][0]['Email']);    
    $message = strip_tags($_POST['form_data'][0]['Message']);
    
    // you can change default Subject for comment form here
    $subject = 'New comment!';
    
    $message = "Message: " . $message . "\r\n"; 
    
    if ($name == "" || $email == "" || $message == "") {
        echo "There was problem while sending E-Mail. Please verify entered data and try again!";
        exit();
    }
} else if ($action == 'book_table') {
    $name = strip_tags($_POST['form_data'][0]['Name']);
    $email = strip_tags($_POST['form_data'][0]['Email']);
    $restaurant = isset($_POST['form_data'][0]['Restaurant']) ? strip_tags($_POST['form_data'][0]['Restaurant']) : 'Not set';
    $date = strip_tags($_POST['form_data'][0]['Date']);
    $time = strip_tags($_POST['form_data'][0]['Time']);
    $guests = strip_tags($_POST['form_data'][0]['Guests']);
    
    // you can change default Subject for booking E-Mail here
    $subject = 'Reservation Successful!';
    
    $message = "Reservation info:\r\n"
                . "Name: " . $name . "\r\n"
                . "Restaurant: " . $restaurant . "\r\n"
                . "Date: " . $date . "\r\n"
                . "Time: " . $time . "\r\n"
                . "Number of guests: " . $guests . "\r\n";

    if ($name == "" || $email == "" || $date == "" || $time == "" || $guests == "") {
        echo "There was problem while sending E-Mail. Please verify entered data and try again!";
        exit();
    }
} 

$headers = 'From: ' . $name . '<' . $sendFrom . ">\r\n" .
        'X-Mailer: PHP/' . phpversion();


$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUsername;
    $mail->Password = $smtpPassword;
    $mail->SMTPSecure = 'tls'; // Use `ssl` if you are using port 465
    $mail->Port = $smtpPort;

    // Recipients
    $mail->setFrom($sendFrom, $sendFromName);
    $mail->addAddress($email, $name);
    $mail->addReplyTo($sendFrom, $sendFromName);

    // Content
    $mail->isHTML(false); // Set email format to plain text
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();

    $mail->clearAllRecipients();

    $mail->Subject = 'New Reservation!';
    $mail->addAddress($bussiness_email, 'boss');
    $mail->send();

    echo 'Message sent successfully.';
} catch (Exception $e) {
    echo "There was a problem while sending the E-Mail. Mailer Error: {$e}";
}
?>
