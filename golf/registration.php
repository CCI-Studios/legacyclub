<?php require("../head.php"); ?>

<h1>Golf Registration</h1>
<div class="form donation golf">
    <div class="messages">
        <!-- Error messages go here go here -->
    </div>
    <noscript><p>JavaScript is required for the registration form.</p></noscript>
    <form action="/golf/submit" method="POST" class="golf-form">
        <fieldset id="info-0" class='player-info'>
            <legend>Player #1</legend>
            <div class="form-row form-first-name">
                <label>
                  <span class="field-label">Name</span>
                  <input type="text" value="" name="player[first-name][]" class="first-name text" required>
                </label>
            </div>
            <div class="form-row form-email">
                <label>
                  <span class="field-label">Email</span>
                  <input type="email" value="" name="player[email][]" class="email text" required>
                </label>
            </div>
            <div class="form-row form-phone">
                <label>
                  <span class="field-label">Phone</span>
                  <input type="tel" value="" name="player[phone][]" class="phone text" required>
                </label>
            </div>
            <div class="form-row form-address">
                <label>
                  <span class="field-label">Address</span>
                  <textarea name="player[address][]" value="" cols="30" rows="2" class="address text" required></textarea>
                </label>
            </div>
            <div class="form-row form-year">
                <label>
                  <span class="field-label">Year Graduated</span>
                  <input type="number" value="" name="player[year][]" class="year text" required>
                </label>
            </div>
        </fieldset>
        <button type="button" class="add-player">Add Player</button>
        <fieldset>
            <legend>Payment</legend>
             <div class="form-row form-first-name">
                <label>
                  <span class="field-label">Name</span>
                  <input type="text" value="" name="name" class="first-name name-bill text" required>
                </label>
            </div>
            <div class="form-row form-email">
                <label>
                  <span class="field-label">Email</span>
                  <input type="email" value="" name="email" class="email email-bill text" required>
                </label>
            </div>
            <div class="form-row form-phone">
                <label>
                  <span class="field-label">Phone</span>
                  <input type="tel" value="" name="phone" class="phone phone-bill text" required>
                </label>
            </div>
            <div class="form-row form-address">
                <label>
                  <span class="field-label">Address</span>
                  <textarea name="address" value="" cols="30" rows="2" class="address address-bill text" required></textarea>
                </label>
            </div>
            
            <div class="form-row form-amount">
                <label>
                  <span class="field-label">Amount</span>
                  <input type="text" disabled name="amount" value="125" class="amount disabled text">
                </label>
            </div>
            <div class="form-row form-number">
                <label>
                  <span class="field-label">Card Number</span>
                  <input type="number" autocomplete="off" class="card-number text" value="" required>
                </label>
            </div>
            <div class="form-row form-cvc">
                <label>
                  <span class="field-label">CVC</span>
                  <input type="number" autocomplete="off" class="card-cvc text" value="" required>
                </label>
            </div>
            <div class="form-row form-expiry">
                <label>
                  <span class="field-label">Expiration Date</span>
                  <select class="card-expiry-month text" required>
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
                  <select class="card-expiry-year text" required>
                    <option value="2017" selected>2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                  </select>
                </label>
            </div>
            <div class="form-row form-submit">
                <button type="submit" class="submit">Process Payment</button>
            </div>
            <div id="messages"></div>
        </fieldset>
        <input type="hidden" name="numPlayers" value="1">
    </form>
</div>
<div id="response"></div>
<div id="loading"><img src="/images/ajax-loader.gif"></div>

<script type="text/javascript" src="https://js.stripe.com/v2"></script>
<script type="text/javascript">
    Stripe.setPublishableKey('<?php echo $config['STRIPE_PUBLISHABLE_KEY']; ?>');
</script>
<script type="text/javascript" src="/js/golfform.js"></script>      
<script type="text/javascript" src="/js/donateform.js"></script>

<script>if (window.Stripe) $('.golf-form').show()</script>

<?php require("../foot.php"); ?>
