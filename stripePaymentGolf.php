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
    $token      = $_POST['stripeToken'];
    $player = [];
   
    foreach($_POST['player'] as $key => $value) {

            $first_name = $value['first-name'];
            $name       = $first_name;
            $address    = $value['address'] . "\n" . $value['city'];
            $email      = $value['email'];
            $phone      = $value['phone'];
            $amount     = (float) $_POST['amount'];

            $playerInfo = $name.','.$address.','.$email.','.$phone.','.$amount;

            array_push($player,$playerInfo);
        
    }

    echo '<pre>';
    print_r($player);
    echo '</pre>';
    die;
   

    try {
        if ( ! isset($_POST['stripeToken']) ) {
            throw new Exception("The Stripe Token was not generated correctly");
        }

        // Charge the card
        $donation = \Stripe\Charge::create(array(
            'card'        => $token,
            'description' => 'Donation by ' . $name . ' (' . $email . ')',
            'amount'      => $amount * 100,
            'currency'    => 'cad',
            "metadata" => array("address" => $address,"Email" => $email,"Phone" => $phone)
        ));

        
        // Build and send the email
       // $headers = 'From: ' . $config['email-from'];
       // $headers .= "\r\nBcc: " . $config['email-bcc'] . "\r\n\r\n";

        $find    = array('%name%', '%amount%');
        $replace = array($name, '$' . $amount);

        $message = str_replace($find, $replace , $config['email-message'])."</br>";
        $message .= '<p>Amount: $' . $amount . "</p>";
        $message .= '<p>Address: ' . $address . "</p>";
        $message .= '<p>Phone: ' . $phone . "</p>";
        $message .= '<p>Email: ' . $email . "</p>";
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
        $mail->addAddress($email, $first_name.' '.$last_name);  // Add a recipient
        $mail->addBCC('bcc@example.com');
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