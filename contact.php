<?php

/*
 * Script for sending E-Mail messages.
 * 
 * Note: Please edit $sendTo variable value to your email address.
 * 
 */

// please change this to your E-Mail address
$sendTo = "your-email@company.com";

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
    $subject = 'New reservation!';
    
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

$headers = 'From: ' . $name . '<' . $email . ">\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

if (mail($sendTo, $subject, $message, $headers)) {
    echo "Message sent succesfully.";
} else {
    echo "There was problem while sending E-Mail.";
}
?>
