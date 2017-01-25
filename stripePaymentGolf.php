<?php

// Load Stripe
require('vendor/stripe/stripe-php/init.php');
require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

// Load configuration settings
$config = require('config.php');

// Force https
if ($config['test-mode'] && $_SERVER['HTTPS'] != 'on') {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: https://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
    exit;
}

$mail = new PHPMailer;

if ($_POST) {
    \Stripe\Stripe::setApiKey($config['secret-key']);

    // POSTed Variables
    $token  = $_POST['stripeToken'];
    $player = [];
    $total = 0;
    $players = '';

    $amount = count($_POST['player']);
    $amount = $amount*100;

    $name_bill = $_POST['name'];
    $email_bill = $_POST['email'];
    $phone_bill = $_POST['phone'];
    $address_bill = $_POST['address'];

    foreach($_POST['player'] as $key => $value) {

            $first_name = $value['first-name'];
            $name       = $first_name;
            $address    = $value['address'] . "\n" . $value['city'];
            $email      = $value['email'];
            $phone      = $value['phone'];

            $playerInfo = "Name: ".$name.","." Address: ".$address.","." Email: ".$email.","." Phone: ".$phone;

            array_push($player,$playerInfo);
    }


    foreach ($player as $key => $value) {
        
        $key++;
        $players .= "<p>Player# ".$key."</p>";
        $players .= "<p>".$value."</p>";

    }

    try {
        if ( ! isset($_POST['stripeToken']) ) {
            throw new Exception("The Stripe Token was not generated correctly");
        }

        // Charge the card
        $donation = \Stripe\Charge::create(array(
            'card'        => $token,
            'description' => 'Donation by ' . $name_bill,
            'amount'      => $amount * 100,
            'currency'    => 'cad',
            "metadata" => array("Players" => $players, "Phone" => $phone_bill, "email" => $email_bill)
        ));

        
       // Build and send the email
       // $headers = 'From: ' . $config['email-from'];
       // $headers .= "\r\nBcc: " . $config['email-bcc'] . "\r\n\r\n";

       $find    = array('%amount%');
       $replace = array('$' . $amount);

        $message = str_replace($find, $replace , $config['email-message-golf'])."</br>";
        $message .= '<p>'.$players.'</p>';
        $message .= '<p>Amount: $' . $amount . "</p>";
        $message .= '<p>Date: ' . date('M j, Y, g:ia', $donation['created']) . "</p>";
        $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
        // Find and replace values

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'hsanvaria@ccistudios.com';                 // SMTP username
        $mail->Password = $config['email-password'];                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        $mail->setFrom($config['email-from'], 'Legacy club');     //Set who the message is to be sent from
        $mail->addAddress($email_bill, $name_bill);  // Add a recipient
        $mail->addBCC('hsanvaria@ccistudios.com');
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $config['email-subject'];
        $mail->Body    = $message;
        $mail->AltBody = $message;

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body

        if(!$mail->send()) {
           echo 'Message could not be sent.';
           echo 'Mailer Error: ' . $mail->ErrorInfo;
           exit;
        }

        // Forward to "Thank You" page
        header('Location: ' . $config['thank-you']);
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
        echo $error;
       
    }
}
?>