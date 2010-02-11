
(function($) {

	$(document).ready(function() {
	});
	
	$('.resolveAction').bind('click', function() {
		var params = '';
		var textareas = $(".resolutionNote[name="+this.id+"]");
		
		// get resolution notes
		$.each(textareas, function( index, object ) {
			params = "resolutionnote=" + object.value;
		});
		
		// add them to the param string
		this.href = this.href+"?"+params;
		return true;
	});

	$('.unresolveAction').bind('click', function() {
		var params = '';
		var textareas = $(".resolutionNote[name="+this.id+"]");
		
		// get resolution notes
		$.each(textareas, function( index, object ) {
			params = "resolutionnote=" + object.value;
		});
		
		// add them to the param string
		this.href = this.href+"?"+params;
		return true;
	});
	

})(jQuery);
