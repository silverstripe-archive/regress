/**
 * This plugin reminds a user if there are changes in the form field (text input, textarea) before navigating away
 * Created: March 25, 2010
 * Author: saophalkun ponlu <phalkunz [at] silverstripe [dot] com
 *
 * Example:
 * 		$(form_selector).changeAwareForm();
 *		$(another_form_selector).changeAwareForm({message: "message in the browser popup"});
 */

(function($) {
	$.fn.changeAwareForm = function(options) {
		settings = $.extend({
			message: ""
		}, options);
		
		var message = settings.message;
		
		return this.each(function(){
			form = $(this);
			textInputs = form.find('input[type=text], textarea');
		
			hasChanged = false; 
		
			textInputs.keypress(function(){
				if(hasChanged) return;
			
				hasChanged = true; 
				window.onbeforeunload = function() {
					if(hasChanged) return message;
				}
			});
		
			form.submit(function() {
				hasChanged = false;
				return true;
			});
	  	});
	}

})(jQuery);