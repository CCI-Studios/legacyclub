<?php
require('../vendor/autoload.php');
$config = require('../config.php');

// POSTed Variables
$token  = $_POST['stripeToken'];
$player = [];
$playerRaw = [];
$total = 0;
$players = '';

$amount = 0;
$isDinnerOnly = true;
if ($_POST['type'] == "golf") {
  $isDinnerOnly = false;
}
if ($isDinnerOnly) {
  $amount = 40;
} else {
  $amount = count($_POST['player']['first-name']) * 150;
}

$name_bill = $_POST['name'];
$email_bill = $_POST['email'];
$phone_bill = $_POST['phone'];
$address_bill = $_POST['address'];

for($i=0; $i<(int)$_POST['numPlayers']; $i++) {
  $name = $_POST['player']['first-name'][$i];
  $email      = $_POST['player']['email'][$i];
  $phone      = $_POST['player']['phone'][$i];
  $graduated  = $_POST['player']['year'][$i];
  $shirt  = $_POST['player']['shirt'][$i];

  $playerInfo = "Name: ".$name.", Email: ".$email.", Phone: ".$phone.", Graduated: ".$graduated.", Shirt Size: ".$shirt;

  $player[] = $playerInfo;
  $playerRaw[] = ['name'=>$name, 'email'=>$email, 'phone'=>$phone, 'graduated'=>$graduated, 'shirt'=>$shirt];
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
    "metadata" => array("Name" => $name_bill, "Phone" => $phone_bill, "email" => $email_bill, "Address" => $address_bill)
  ));

  $mysqli = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);
  if ($mysqli->connect_error) {
    throw new Exception('You payment has been processed and there was a problem connecting to the database. Please contact the administrator.');
  }
  if ($stmt = $mysqli->prepare("INSERT INTO golf_registrations (billing_name, billing_email, billing_phone, billing_address, billing_amount, player1_name, player1_email, player1_phone, player1_graduated, player1_shirt, player2_name, player2_email, player2_phone, player2_graduated, player2_shirt, player3_name, player3_email, player3_phone, player3_graduated, player3_shirt, player4_name, player4_email, player4_phone, player4_graduated, player4_shirt, stripe_transaction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ")) {
    $count = count($playerRaw);
    for ($i=0; $i<4-$count; $i++) {
      $playerRaw[] = array('name'=>'', 'email'=>'', 'phone'=>'', 'graduated'=>'', 'shirt'=>'');
    }
    $int_amount = $amount * 100;
    $player1_name = $playerRaw[0]['name'];
    $player1_email = $playerRaw[0]['email'];
    $player1_phone = $playerRaw[0]['phone'];
    $player1_graduated = $playerRaw[0]['graduated'];
    $player1_shirt = $playerRaw[0]['shirt'];
    $player2_name = $playerRaw[1]['name'];
    $player2_email = $playerRaw[1]['email'];
    $player2_phone = $playerRaw[1]['phone'];
    $player2_graduated = $playerRaw[1]['graduated'];
    $player2_shirt = $playerRaw[1]['shirt'];
    $player3_name = $playerRaw[2]['name'];
    $player3_email = $playerRaw[2]['email'];
    $player3_phone = $playerRaw[2]['phone'];
    $player3_graduated = $playerRaw[2]['graduated'];
    $player3_shirt = $playerRaw[2]['shirt'];
    $player4_name = $playerRaw[3]['name'];
    $player4_email = $playerRaw[3]['email'];
    $player4_phone = $playerRaw[3]['phone'];
    $player4_graduated = $playerRaw[3]['graduated'];
    $player4_shirt = $playerRaw[3]['shirt'];
    $stripe_transaction = $donation['id'];
    $stmt->bind_param("ssssisssssssssssssssssssss", $name_bill, $email_bill, $phone_bill, $address_bill, $int_amount, $player1_name, $player1_email, $player1_phone, $player1_graduated, $player1_shirt, $player2_name, $player2_email, $player2_phone, $player2_graduated, $player2_shirt, $player3_name, $player3_email, $player3_phone, $player3_graduated, $player3_shirt, $player4_name, $player4_email, $player4_phone, $player4_graduated, $player4_shirt, $stripe_transaction);
    if (!$stmt->execute()) {
      throw new Exception('Your payment was processed and then there was an error. Please contact the administrator.');
    }
  }
  $mysqli->close();

  //make the email content to the user
  date_default_timezone_set('America/Toronto');
  $date = date('M j, Y, g:ia');
  $message = "<p>Thank you for registering for the 2019 Count On Me Golf Tournament.</p><p>This yearly event is presented by the Fighting Irish Legacy Club and all proceeds will go to support St. Patrick's Fighting Irish Football.</p><p>Your registration details are as follows:</p>";
  if ($isDinnerOnly) {
    $message .= '<p>Dinner registration only.</p>';
    $message .= "<p>$name_bill<br>$email_bill</p>";
  } else {
    $message .= '<p>'.$players.'</p>';
  }
  $message .= '<p>Amount: $' . $amount . "</p>";
  $message .= '<p>Date Registered: ' . $date . "</p>";
  $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
  $message .= "<p>We very much appreciate your support and look forward to seeing you on July 13th, 2019 at Huron Oaks!</p>";
  $message .= "<p>Count On Me,<br>Fighting Irish Legacy Club</p>";
  $message .= "<p><a href='https://www.legacyclub.ca'>www.legacyclub.ca</a></p>";
  
  //send email to user
  $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
  $subject = "Count On Me Golf Tournament Registration 2019";
  $to = new SendGrid\Email($name_bill, $email_bill);
  $content = new SendGrid\Content("text/html", $message);
  $mail = new SendGrid\Mail($from, $subject, $to, $content);
  $sg = new \SendGrid($config['SENDGRID_API_KEY']);
  $response = $sg->client->mail()->send()->post($mail);
  
  //make the email content to the admin
  $message = "<p>Golf registration details are as follows:</p>";
  if ($isDinnerOnly) {
    $message .= '<p>Dinner registration only.</p>';
    $message .= "<p>$name_bill<br>$email_bill</p>";
  } else {
    $message .= '<p>'.$players.'</p>';
  }
  $message .= '<p>Amount: $' . $amount . "</p>";
  $message .= '<p>Date Registered: ' . $date . "</p>";
  $message .= '<p>Transaction ID: ' . $donation['id'] . "</p>";
  
  //send email to admin
  $from = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['FROM_EMAIL_ADDRESS']);
  $subject = "Count On Me Golf Tournament Registration 2019";
  $to = new SendGrid\Email($config['FROM_EMAIL_NAME'], $config['ADMIN_EMAIL']);
  $content = new SendGrid\Content("text/html", $message);
  $mail = new SendGrid\Mail($from, $subject, $to, $content);
  if (isset($config['ADMIN_CC_EMAIL']) && $config['ADMIN_CC_EMAIL']) {
    $cc_addresses = explode(',', $config['ADMIN_CC_EMAIL']);
    foreach ($cc_addresses as $cc_address) {
      $cc = new SendGrid\Email(null, $cc_address);
      $mail->personalization[0]->addCC($cc);
    }
  }
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