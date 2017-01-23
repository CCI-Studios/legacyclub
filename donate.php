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
    $first_name = $_POST['first-name'];
    $last_name  = $_POST['last-name'];
    $name       = $first_name . ' ' . $last_name;
    $address    = $_POST['address'] . "\n" . $_POST['city'] . ', ' . $_POST['state'] . ' ' . $_POST['zip'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $amount     = (float) $_POST['amount'];

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Donation Form</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript">
        Stripe.setPublishableKey('<?php echo $config['publishable-key'] ?>');
    </script>
    <script type="text/javascript" src="script.js"></script>
</head>
<body>
    <div id="wrapper">
            <div id="header">
                <div id="header-inner">
                    <div id="logo"><img src="images/logo.png" alt="logo"></div>
                </div>
            </div>
        
            <div id="content">
                <div id="content-inner">
                    <div id="top-text">
                        <div>
                            <h1>Donation</h1>
                            <div class="form donation">
                                <div class="messages">
                                    <!-- Error messages go here go here -->
                                </div>
                                  <form action="#" method="POST" class="donation-form">
                                        <fieldset>
                                            <legend>
                                                Contact Information
                                            </legend>
                                            <div class="form-row form-first-name">
                                                <label>First Name</label>
                                                <input type="text" name="first-name" class="first-name text">
                                            </div>
                                            <div class="form-row form-last-name">
                                                <label>Last Name</label>
                                                <input type="text" name="last-name" class="last-name text">
                                            </div>
                                            <div class="form-row form-email">
                                                <label>Email</label>
                                                <input type="text" name="email" class="email text">
                                            </div>
                                            <div class="form-row form-phone">
                                                <label>Phone</label>
                                                <input type="text" name="phone" class="phone text">
                                            </div>
                                            <div class="form-row form-address">
                                                <label>Address</label>
                                                <textarea name="address" cols="30" rows="2" class="address text"></textarea>
                                            </div>
                                            <div class="form-row form-city">
                                                <label>City</label>
                                                <input type="text" name="city" class="city text">
                                            </div>
                                            <div class="form-row form-state">
                                                <label>State</label>
                                                <select name="state" class="state text">
                                                    <option value="AB">Alberta</option>
                                                    <option value="BC">British Columbia</option>
                                                    <option value="MB">Manitoba</option>
                                                    <option value="NB">New Brunswick</option>
                                                    <option value="NL">Newfoundland and Labrador</option>
                                                    <option value="NS">Nova Scotia</option>
                                                    <option value="ON">Ontario</option>
                                                    <option value="PE">Prince Edward Island</option>
                                                    <option value="QC">Quebec</option>
                                                    <option value="SK">Saskatchewan</option>
                                                    <option value="NT">Northwest Territories</option>
                                                    <option value="NU">Nunavut</option>
                                                    <option value="YT">Yukon</option>
                                                </select>
                                            </div>
                                            <div class="form-row form-zip">
                                                <label>Zip</label>
                                                <input type="text" name="zip" class="zip text">
                                            </div>
                                        </fieldset>

                                        <fieldset>
                                           <legend>
                                                Payment
                                            </legend>
                                            <div class="form-row form-amount">
                                                <label>Amount:</label> <input type="text" name="amount" class="amount text">
                                            </div>
                                            <div class="form-row form-number">
                                                <label>Card Number</label>
                                                <input type="text" autocomplete="off" class="card-number text" value="4242424242424242">
                                            </div>
                                            <div class="form-row form-cvc">
                                                <label>CVC</label>
                                                <input type="text" autocomplete="off" class="card-cvc text" value="123">
                                            </div>
                                            <div class="form-row form-expiry">
                                                <label>Expiration Date</label>
                                                <select class="card-expiry-month text">
                                                    <option value="01" selected>January</option>
                                                    <option value="02">February</option>
                                                    <option value="03">March</option>
                                                    <option value="04">April</option>
                                                    <option value="05">May</option>
                                                    <option value="06">June</option>
                                                    <option value="07">July</option>
                                                    <option value="08">August</option>
                                                    <option value="09">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <select class="card-expiry-year text">
                                                    <option value="2015">2015</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2017" selected>2017</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2020">2020</option>
                                                </select>
                                            </div>
                                            <div class="form-row form-submit">
                                                <input type="submit" class="submit" value="Submit Donation">
                                            </div>
                                        </fieldset>
                                </form>
                            </div>
                            <div id="response"></div>
                            <div id="loading"><img src="images/ajax-loader.gif"></div>
                         </div>
                    </div>
                </div>
            <div id="footer">
                <div>
                    <h3>Follow us on social</h3>
                    <span><a href="https://www.facebook.com/FightingIrishLC" target="_blank"><img src="images/fb.png"></a></span>
                    <span><a href="https://twitter.com/fightingirishlc" target="_blank"><img src="images/twitter.png"></a></span>           
                </div>
            </div>
        </div>

    </body>
    <div class="wrapper">

    
       

     
    </div>

    <script>if (window.Stripe) $('.donation-form').show()</script>
    <noscript><p>JavaScript is required for the donation form.</p></noscript>

</body>
</html>
