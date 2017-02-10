<?php

require('../vendor/autoload.php');
$config = require('../config.php');

try {
    if (!isset($_POST['stripeToken'])) {
        throw new Exception("The Stripe Token was not generated correctly");
    }
    
    // POSTed Variables
    $token      = $_POST['stripeToken'];
    $name = $_POST['first-name'];
    $address    = $_POST['address'] . "\n" . $_POST['city'] . ', ' . $_POST['state'] . ' ' . $_POST['zip'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $amount     = (float) $_POST['amount'];

    // Charge the card
    \Stripe\Stripe::setApiKey($config['STRIPE_SECRET_KEY']);
    $donation = \Stripe\Charge::create(array(
        'card'        => $token,
        'description' => 'Donation to Legacy Club',
        'amount'      => $amount * 100,
        'currency'    => 'cad',
        "metadata" => array("address" => $address,"Email" => $email,"Phone" => $phone)
    ));
    
    //make the email message content
    $find    = array('%name%', '%amount%');
    $replace = array($name, '$' . $amount);
    $message = "Dear %name%,\n\nThank you for your donation of %amount%. We rely on the financial support from people like you to keep our cause alive. Below is your donation receipt to keep for your records.";
    $message = str_replace($find, $replace , $message)."</br>";
    $message .= '<p>Amount: $' . $amount . "</p>";
    $message .= '<p>Address: ' . $address . "</p>";
    $message .= '<p>Phone: ' . $phone . "</p>";
    $message .= '<p>Email: ' . $email . "</p>";
    $message .= '<p>Date: ' . date('M j, Y, g:ia', $donation['created']) . "</p>";
    $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
    
    //send email to user
    $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
    $subject = "Thank you for your donation!";
    $to = new SendGrid\Email($name, $email);
    $content = new SendGrid\Content("text/html", $message);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    $sg = new \SendGrid($config['SENDGRID_API_KEY']);
    $response = $sg->client->mail()->send()->post($mail);

    // Forward to "Thank You" page
    header('Location: /donate/thankyou');
    exit;

} catch (Exception $e) {
    $error = $e->getMessage();
    echo $error;
}

?>