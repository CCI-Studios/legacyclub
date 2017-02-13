<?php
require('../vendor/autoload.php');
$config = require('../config.php');

// POSTed Variables
$token  = $_POST['stripeToken'];
$player = [];
$total = 0;
$players = '';

$amount = count($_POST['player']);
$amount = $amount*125;

$name_bill = $_POST['name'];
$email_bill = $_POST['email'];
$phone_bill = $_POST['phone'];
$address_bill = $_POST['address'];

foreach($_POST['player'] as $key => $value) {
  $first_name = $value['first-name'];
  $name       = $first_name;
  $address    = $value['address'];
  $email      = $value['email'];
  $phone      = $value['phone'];
  $graduated  = $value['year'];

  $playerInfo = "Name: ".$name.","." Address: ".$address.","." Email: ".$email.","." Phone: ".$phone." Graduated: ".$graduated;

  $player[] = $playerInfo;
}

foreach ($player as $key => $value) {
  $players .= "<p>Player# ".($key+1)."</p>";
  $players .= "<p>".$value."</p>";
}

try {
  if (!isset($_POST['stripeToken'])) {
    throw new Exception("The Stripe Token was not generated correctly");
  }

  // Charge the card
  \Stripe\Stripe::setApiKey($config['STRIPE_SECRET_KEY']);
  $donation = \Stripe\Charge::create(array(
    'card'        => $token,
    'description' => 'Golf Registration for ' . count($player) . ' players',
    'amount'      => $amount * 100,
    'currency'    => 'cad',
    "metadata" => array("Players" => $players, "Phone" => $phone_bill, "email" => $email_bill, "Address" => $address_bill)
  ));

  //make the email content to the user
  date_default_timezone_set('America/Toronto');
  $date = date('M j, Y, g:ia');
  $message = "<p>Thank you for registering for the 2017 Count On Me Golf Tournament.</p><p>This yearly event is presented by the Fighting Irish Legacy Club and all proceeds will go to support St. Patrick's Fighting Irish Football.</p><p>Your registration details are as follows:</p>";
  $message .= '<p>'.$players.'</p>';
  $message .= '<p>Amount: $' . $amount . "</p>";
  $message .= '<p>Date Registered: ' . $date . "</p>";
  $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
  $message .= "<p>We very much appreciate your support and look forward to seeing you on July 15th, 2017 at Huron Oaks!</p>";
  $message .= "<p>Count On Me,<br>Fighting Irish Legacy Club</p>";
  $message .= "<p><a href='https://www.legacyclub.ca'>www.legacyclub.ca</a></p>";
  
  //send email to user
  $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
  $subject = "Count On Me Golf Tournament Registration 2017";
  $to = new SendGrid\Email($name_bill, $email_bill);
  $content = new SendGrid\Content("text/html", $message);
  $mail = new SendGrid\Mail($from, $subject, $to, $content);
  $sg = new \SendGrid($config['SENDGRID_API_KEY']);
  $response = $sg->client->mail()->send()->post($mail);
  
  //make the email content to the admin
  $message = "<p>Golf registration details are as follows:</p>";
  $message .= '<p>'.$players.'</p>';
  $message .= '<p>Amount: $' . $amount . "</p>";
  $message .= '<p>Date Registered: ' . $date . "</p>";
  $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
  
  //send email to admin
  $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
  $subject = "Count On Me Golf Tournament Registration 2017";
  $to = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['ADMIN_EMAIL']);
  $content = new SendGrid\Content("text/html", $message);
  $mail = new SendGrid\Mail($from, $subject, $to, $content);
  $sg = new \SendGrid($config['SENDGRID_API_KEY']);
  $response = $sg->client->mail()->send()->post($mail);

  // Forward to "Thank You" page
  header('Location: /golf/thankyou');
  exit;

} catch (Exception $e) {
  $error = $e->getMessage();
  echo $error;
}
?>