<?php require("../head.php"); ?>

<div class="golf-heading">
	<h2><img src="/images/golf-title-2019.png" alt="Count On Me Golf Tournament" /></h2>
	<h3>Join us for a scramble golf tournament in support of St. Patrick’s Fighting Irish Football</h3>
	<p><strong>When:</strong> Saturday, July 13, 2019, 12pm Registration/Lunch, 1pm Shotgun Start<br>
		<strong>Where:</strong> Huron Oaks Golf Course<br>
		<strong>Cost:</strong> $125 per person (includes 18 holes of golf, cart, lunch &amp; dinner)
	</p>
	<div class="golf-button-container">
		<a href="/golf/registration" class="golf-button golf-button-first"><span>Click here to register</span></a>
		<a href="/images/Legacy%20Club%20-%20Sponsorship%20Package%20-%202019.pdf" target="_blank" class="golf-button golf-button-last"><span>Click here to sponsor [PDF]</span></a>
	</div>
</div>

<h3>Stay up to date with the fighting irish</h3>
<div class="form-signup">
	<form id="signup" action="/ajax/subscribe.php" method="post">
		<div class="form-first-name textfield">
			<label>
				<span class="field-label">First Name *</span>
				<input name="fname" id="fname" type="text" required />
			</label>
		</div>
		<div class="form-last-name textfield">
			<label>
				<span class="field-label">Last Name *</span>
				<input name="lname" id="lname" type="text" required />
			</label>
		</div>
		<div class="email emailfield">
			<label>
				<span class="field-label">Email Address *</span>
				<input name="email" id="email" type="email" required />
			</label>
		</div>
		<div class="form-submit">
			<button class="submit" type="submit">Submit</button>
		</div>
	</form>
</div>
<div id="response"></div>
<div id="loading"><img src="/images/ajax-loader.gif"></div>


<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
			// jQuery Validation
		$("#signup").validate({
			// if valid, post data via AJAX
			submitHandler: function(form) {
				$('#loading').show();
				$.post("/ajax/subscribe", { fname: $("#fname").val(), lname: $("#lname").val(), email: $("#email").val() }, function(data) {
					$('#response').html(data);
					$('#loading').hide();
				});
			},
			// all fields are required
			rules: {
				fname: {
					required: true
				},
				lname: {
					required: true
				},
				email: {
					required: true,
					email: true
				}
			}
		});
	});
</script>

<?php require("../foot.php"); ?>