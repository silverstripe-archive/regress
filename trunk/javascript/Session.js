(function($) {

	$(document).ready(function() {
	});
	
	$('.resolveAction, .unresolveAction, .commentAction').bind('click', function() {
		var params = '';
		
		// get resolution notes
		var textareas = $(".resolutionNote[name="+this.id+"]");
		$.each(textareas, function( index, object ) {
			params = "resolutionnote=" + object.value;
		});
		
		// get severity rating
		var severity = $('input[name='+this.id+']:checked');
		$.each(severity, function( index, object ) {
			if (params) params += "&";
			params += "severity=" + object.value;
		});
		
		// add them to the param string
		this.href = this.href+"?"+params;
		return true;
	});
	
})(jQuery);
