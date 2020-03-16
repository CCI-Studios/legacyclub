$(function(){
	
	var $form = $(".golf-form");
	var count = 1;
	var amount = 150;
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

			$(set).find("input:not([type=radio]), textarea").val("");
			$(set).find("input[type=radio]").prop("checked",false).attr("name","player[shirt]["+(count-1)+"]");
			$(set).find("legend").append('<a href="#" class="remove-player">remove</a>');
			$(set).insertBefore('.add-player');
			amount = count*150;
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
		updatePrice();
	});
	$form.on("click","input[name=type]", function () {
		if ($form.find("input[name=type]:checked").val() === "golf") {
			$form.find(".player-info").show().removeAttr("disabled");
			count = $form.find(".player-info").length;
			if (count < 4) {
				$form.find(".add-player").show();
			}
		} else {
			count = 0;
			$form.find(".player-info").hide().attr("disabled", "disabled");
			$form.find(".player-info, .add-player").hide();
		}
		updatePrice();
	});

	function updatePrice () {
		if ($form.find("input[name=type]:checked").val() === "golf") {
			amount = count*150;
		} else {
			count = 0;
			amount = 40;
		}
		$('input.amount').val("CAD$"+amount+".00");
		$("[name=numPlayers]").val(count);
	}
	
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
