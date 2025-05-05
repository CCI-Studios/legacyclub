<?php require("../head.php"); ?>

<h1>Donation</h1>
<div class="form donation">
    <noscript><p>JavaScript is required for the donation form.</p></noscript>
      <form action="/donate/submit" method="POST" class="donation-form">
        <fieldset>
          <legend>Contact Information</legend>
          <div class="form-row form-first-name">
              <label>
                <span class="field-label">Name</span>
                <input type="text" name="first-name" class="first-name name-bill text">
              </label>
          </div>
          <div class="form-row form-email">
              <label>
                <span class="field-label">Email</span>
                <input type="email" name="email" class="email text">
              </label>
          </div>
          <div class="form-row form-phone">
              <label>
                <span class="field-label">Phone</span>
                <input type="tel" name="phone" class="phone text">
              </label>
          </div>
          <div class="form-row form-address">
              <label>
                <span class="field-label">Address</span>
                <textarea name="address" cols="30" rows="2" class="address address-bill text"></textarea>
              </label>
          </div>
          <div class="form-row form-city">
              <label>
                <span class="field-label">City</span>
                <input type="text" name="city" class="city text">
              </label>
          </div>
          <div class="form-row form-state">
              <label>
                <span class="field-label">Province</span>
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
              </label>
          </div>
          <div class="form-row form-zip">
              <label>
                <span class="field-label">Postal Code</span>
                <input type="text" name="zip" class="zip text">
              </label>
          </div>
        </fieldset>

        <fieldset>
           <legend>Payment</legend>
            <div class="form-row form-amount">
                <label>
                  <span class="field-label">Amount</span>
                  <input type="number" name="amount" class="amount text">
                </label>
            </div>
            <div class="form-row form-number">
                <label>
                  <span class="field-label">Card Number</span>
                  <input type="number" autocomplete="off" class="card-number text" value="">
                </label>
            </div>
            <div class="form-row form-cvc">
                <label>
                  <span class="field-label">CVC</span>
                  <input type="number" autocomplete="off" class="card-cvc text" value="">
                </label>
            </div>
            <div class="form-row form-expiry">
                <label>
                  <span class="field-label">Expiration Date</span>
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
                </label>
            </div>
            <div class="form-row form-submit">
                <button type="submit" class="submit">Submit Donation</button>
            </div>
            <div class="messages"></div>
        </fieldset>
    </form>
</div>
<div id="response"></div>
<div id="loading"><img src="/images/ajax-loader.gif"></div>

<script type="text/javascript" src="https://js.stripe.com/v2"></script>
<script type="text/javascript">
    Stripe.setPublishableKey('<?php echo $config['STRIPE_PUBLISHABLE_KEY'] ?>');
</script>
<script type="text/javascript" src="/js/donateform.js"></script>
<script>if (window.Stripe) $('.donation-form').show()</script>

<?php require("../foot.php"); ?>
