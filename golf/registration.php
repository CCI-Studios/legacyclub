<?php require("../head.php"); ?>

<h1>2025 Golf Tournament Registration</h1>
<div class="form donation golf">
    <div class="messages">
        <!-- Error messages go here go here -->
    </div>
    <noscript><p>JavaScript is required for the registration form.</p></noscript>
    <form action="/golf/submit" method="POST" class="golf-form">

        <fieldset>
            <legend>Registration Type</legend>
            <div class="form-radios">
                <label>
                    <input type="radio" value="golf" name="type" class="radio" checked>
                    <span>Golf</span>
                </label>
                <label>
                    <input type="radio" value="dinner" name="type" class="radio">
                    <span>Dinner Only</span>
                </label>
            </div>
        </fieldset>

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
            <div class="form-row form-year">
                <label>
                  <span class="field-label">Year Graduated</span>
                  <input type="number" value="" name="player[year][]" class="year text" required>
                </label>
            </div>
            <div class="form-row form-shirt-size">
                <label>
                  <span class="field-label">T-Shirt Size</span>
                </label>
                <div class="form-radios">
                  <label>
                    <input type="radio" value="S" name="player[shirt][]" class="shirt radio">
                    <span>SMALL</span>
                  </label>
                  <label>
                    <input type="radio" value="M" name="player[shirt][]" class="shirt radio">
                    <span>MEDIUM</span>
                  </label>
                  <label>
                    <input type="radio" value="L" name="player[shirt][]" class="shirt radio">
                    <span>LARGE</span>
                  </label>
                  <label>
                    <input type="radio" value="XL" name="player[shirt][]" class="shirt radio">
                    <span>XL</span>
                  </label>
                  <label>
                    <input type="radio" value="XXL" name="player[shirt][]" class="shirt radio">
                    <span>XXL</span>
                  </label>
                </div>
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
                  <input type="text" disabled name="amount" value="CAD$150.00" class="amount disabled text">
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
                </label>
                <select class="card-expiry-year text" required>
                  <option value="2025" selected>2025</option>
                  <option value="2026">2026</option>
                  <option value="2027">2027</option>
                  <option value="2028">2028</option>
                  <option value="2029">2029</option>
                  <option value="2030">2030</option>
                  <option value="2031">2031</option>
                  <option value="2032">2032</option>
                  <option value="2033">2033</option>
                  <option value="2034">2034</option>
                  <option value="2035">2035</option>
              </select>

            </div>
            <div class="form-row form-submit">
                <button type="submit" class="submit">Process Payment</button>
            </div>
            <div class="messages"></div>
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
<script type="text/javascript" src="/js/golfform.js?v=2"></script>
<script>if (window.Stripe) $('.golf-form').show()</script>

<?php require("../foot.php"); ?>
