<?php require("head.php"); ?>

<div class="home-golf-heading">
	<a href="/golf">
		<img src="/images/golf2018.png" alt="2nd Annual Count On Me Golf Tournament. Saturday, July 14, 2018 at Huron Oaks Golf Course. $125 per person. Registration/Lunch: 12pm. Shotgun Start: 1pm. Includes: Green fees, cart, lunch, dinner contests. Join us for a scramble golf tournament in support of St. Patrick's Fighting Irish Football. Click here to register. The Fighting Irish Legacy Club is an independent non-profit organization that is not directly affiliated with St. Patrick's High School or the St. Clair Catholic District School Board." />
	</a>
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

<?php require("foot.php"); ?>