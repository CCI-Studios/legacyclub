<?php require 'stripePaymentGolf.php';
$config = require('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fighting Irish Legacy Club | St. Patrick's Fighting Irish | Donation Form</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/style.css?v1='123'" media="all">
    <script type="text/javascript" src="https://js.stripe.com/v2"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript">
        Stripe.setPublishableKey('<?php echo $config['publishable-key'] ?>');
    </script>
    <script type="text/javascript" src="golf.js"></script>
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
                                  <form action="" method="POST" class="donation-form">
                                        <fieldset id="info-0" class='player-info'>
                                            <legend>
                                                Player #1
                                            </legend>
                                            <div class="form-row form-first-name">
                                                <label>Name</label>
                                                <input type="text" name="player[1][first-name]" class="first-name text">
                                            </div>
                                            <div class="form-row form-email">
                                                <label>Email</label>
                                                <input type="text" name="player[1][email]" class="email text">
                                            </div>
                                            <div class="form-row form-phone">
                                                <label>Phone</label>
                                                <input type="text" name="player[1][phone]" class="phone text">
                                            </div>
                                            <div class="form-row form-address">
                                                <label>Address</label>
                                                <textarea name="player[1][address]" cols="30" rows="2" class="address text"></textarea>
                                            </div>
                                            <div class="form-row form-city">
                                                <label>City</label>
                                                <input type="text" name="player[1][city]" class="city text">
                                            </div>
                                            <div class="form-row form-year">
                                                <label>Year Graduated</label>
                                                <input type="text" name="player[1][year]" class="year text">
                                            </div>
                                        </fieldset>
                                        <div id="add-player" class="button">Add Player</div>
                                        <fieldset>
                                            <legend>
                                                Payment
                                            </legend>
                                            <div class="form-row form-amount">
                                                <label>Amount:</label><input type="text" disable name="amount" value="" class="amount text">
                                            </div>
                                            <div class="form-row form-number">
                                                <label>Card Number</label>
                                                <input type="text" autocomplete="off" class="card-number text" value="">
                                            </div>
                                            <div class="form-row form-cvc">
                                                <label>CVC</label>
                                                <input type="text" autocomplete="off" class="card-cvc text" value="">
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
  
    <script>if (window.Stripe) $('.donation-form').show()</script>
    <noscript><p>JavaScript is required for the donation form.</p></noscript>

</body>
</html>
