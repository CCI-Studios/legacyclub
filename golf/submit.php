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
  $address    = $value['address'] . "\n" . $value['city'];
  $email      = $value['email'];
  $phone      = $value['phone'];

  $playerInfo = "Name: ".$name.","." Address: ".$address.","." Email: ".$email.","." Phone: ".$phone;

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

  //make the email content
  $find    = array('%amount%');
  $replace = array('$' . $amount);
  $message = "Thank you for your donation of %amount%. We rely on the financial support from people like you to keep our cause alive. Below is your donation receipt to keep for your records.";
  $message = str_replace($find, $replace , $message)."</br>";
  $message .= '<p>'.$players.'</p>';
  $message .= '<p>Amount: $' . $amount . "</p>";
  $message .= '<p>Date: ' . date('M j, Y, g:ia', $donation['created']) . "</p>";
  $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
  
  //send email to user
  $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
  $subject = "Golf Registration";
  $to = new SendGrid\Email($name_bill, $email_bill);
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