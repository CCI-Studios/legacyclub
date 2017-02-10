<?php require("head.php"); ?>

<h1>Full Website Coming Soon</h1>
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
		<div>
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