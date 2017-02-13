$(function(){
	
	var $form = $(".golf-form");
	var count = 1;
	var amount = 125;
	$('.add-player').click(function(){

		count++;

		if($('fieldset.player-info').length < 4)
		{
			var set = $('#info-0').clone();
			$(set).each(function(i,value){

				$(this).find('legend').text('Player #'+count);
				var id = $(this).attr('id');
				var fieldset = id.substring(0, id.lastIndexOf("-") + 1);
				$(this).attr('id',fieldset+''+count);
			});

			$(set).find("input, textarea").val("");
			$(set).find("legend").append('<a href="#" class="remove-player">remove</a>');
			$(set).insertBefore('.add-player');
			amount = count*125;
			$('input.amount').val("CAD$"+amount+".00");
			$("[name=numPlayers]").val(count);
		}
		if (count == 4) {
			$(".add-player").hide();
		}
	});
	$form.on("click",".remove-player", function(e){
		e.preventDefault();
		$(this).closest("fieldset").remove();
		count--;
		$(".add-player").show();
		amount = count*125;
		$('input.amount').val("CAD$"+amount+".00");
		$("[name=numPlayers]").val(count);
	});
	
	$form.on('submit', function(e) {
		e.preventDefault();
		// Disable processing button to prevent multiple submits
		$form.find('button[type=submit]')
			.prop('disabled', true)
			.text('Processing...');
		
		// Create Stripe token, check if CC information correct
		Stripe.createToken({
			name      : $('.name-bill').val(),
		 	address_line1: $('.address-bill').val(),
			number    : $('.card-number').val(),
			cvc       : $('.card-cvc').val(),
			exp_month : $('.card-expiry-month').val(),
			exp_year  : $('.card-expiry-year').val()
		}, stripeResponseHandler);
	});
	function outputError(error) {
		$('.messages').html('<p>' + error + '</p>');
		$form.find('button[type=submit]')
			.removeProp('disabled')
			.text('Process Payment');
	}
	function stripeResponseHandler(status, response) {
		if (response.error) {
			outputError(response.error.message);
		} else {
			var token = response['id'];
			$form.append('<input type="hidden" name="stripeToken" value="' + token + '">');
			$form.get(0).submit();
		}
	}
});
