

$(function(){
	
	var count = 1;
	$('#add-player').click(function(){

		count++;
		console.log($('fieldset.player-info').length);
		if($('fieldset.player-info').length < 4)
		{
			var set = $('#info-0').clone();

			$(set).each(function(i,value){

				$(this).find('legend').text('Player #'+count);
				var id = $(this).attr('id');
				var fieldset = id.substring(0, id.lastIndexOf("-") + 1);
				$(this).attr('id',fieldset+''+count);
			});

			$(set).find('.form-row').each(function(i,value){
				
				if($(this).find('input').length > 0)
				{	
					var str = $(this).find('input').attr('name');
					var rest = str.substring(0, str.indexOf("[") + 1);
					var last = str.substring(str.indexOf("]"), str.length);
					$(this).find('input').attr('name',rest+count+last);
				}
				else
				{	
					var str = $(this).find('textarea').attr('name');
					var rest = str.substring(0, str.indexOf("[") + 1);
					var last = str.substring(str.indexOf("]"), str.length);
					
					$(this).find('textarea').attr('name',rest+count+last);
				}
				console.log(value);
			});
			$(set).insertBefore('#add-player');
		}
		else
		{
			$('<p class="error">You can only add upto 4 players</p>').insertAfter('#add-player');
		}
	});

});
