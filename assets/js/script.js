$(function(){

	$('#countdown').countdown({
		timestamp	: new Date(2013, 04, 24, 12, 30),
		callback	: function(days, hours, minutes, seconds){

			var message = "";

			message += days + " day" + ( days===1 ? '':'s' ) + ", ";
			message += hours + " hour" + ( hours===1 ? '':'s' ) + ", ";
			message += minutes + " minute" + ( minutes===1 ? '':'s' ) + " and ";
			message += seconds + " second" + ( seconds===1 ? '':'s' ) + " <br />";

			message += "left to deadline!";

			$("p#note").html(message);
		}
	});

});
