/*
	Script for Simple Donation Form
	Handles validation and form processing
*/

$(function() {
	var $form        = $('.donation-form');
	var outputError = function(error) {
		$('.messages')
			.html('<p>' + error + '</p>')
			.addClass('active');
		$('.donation .submit')
			.removeProp('disabled')
			.val('Submit Donation');

		$('.donation.golf .submit')
			.removeProp('disabled')
			.val('Process Payment');
	};
	
	var stripeResponseHandler = function(status, response) {
		if (response.error) {
			outputError(response.error.message);
		} else {
			var token = response['id'];
			$form.append('<input type="hidden" name="stripeToken" value="' + token + '">');
			$form.get(0).submit();
		}
	};

	$form.on('submit', function(e) {
		e.preventDefault();
		// Disable processing button to prevent multiple submits
		$form.find('button[type=submit]')
			.prop('disabled', true)
			.val('Processing...');

		// Very simple validation

		$('fieldset.player-info input').each(function(){

			if($(this).val() === '')
			{
				$(this).focus();
				return false;
			}

		});

		if ( $('.first-name').val() === '' ) {
			outputError('Name is required');
			$('.first-name').not('.first-name.name-bill').focus();
			return false;
		}

		if ( $('.first-name.name-bill').val() === '' ) {
			outputError('Card Name is required');
			$('.name-bill').focus();
			return false;
		}

		if ( $('.email.email-bill').val() === '' ) {
			outputError('Email is required');
			$('.email-bill').focus();
			return false;
		}

		if ( $('.address.address-bill').val() === '' ) {
			outputError('Address is required');
			$('.address-bill').focus();
			return false;
		}
	
		if ( $('.email').val() === '' ) {
			outputError('Email is required');
			$('.email').not('.email.email-bill').focus();
			return false;
		}
		if ( $('.phone').val() === '' ) {
			outputError('Phone is required');
			$('.phone').focus();
			return false;
		}
		if ( $('.address').val() === '' ) {
			outputError('Address is required');
			$('.address').not('.address.address-bill').focus();
			return false;
		}
		if ( $('.city').val() === '' ) {
			outputError('City is required');
			$('.city').focus();
			return false;
		}
		if ( $('.zip').val() === '' ) {
			outputError('Zip code is required');
			$('.zip').focus();
			return false;
		}
		if ( $('.amount').val() === '' ) {
			outputError('Please make a donation amount');
			$('.amount').focus();
			return false;
		}

		// Create Stripe token, check if CC information correct
		Stripe.createToken({
			name      : $('.name-bill').val(),
		 	address_line1   : $('.address-bill').val(),
			number    : $('.card-number').val(),
			cvc       : $('.card-cvc').val(),
			exp_month : $('.card-expiry-month').val(),
			exp_year  : $('.card-expiry-year').val()
		}, stripeResponseHandler);
	});

});
