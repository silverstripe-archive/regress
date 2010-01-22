
(function($) {
$(document).ready(function() {
	
	$('input[name=action_doSaveSession]').livequery('click', function(){
		var form = $('form');
		var formAction = form.attr('action') + '?' + 'action_doSaveSession=Execute'

		$.post(formAction, form.formToArray(), function(result){
			var response = eval('(' + result + ')');
			statusMessage(response.Message, 'good');
			
			$('#TestSessionObjID').val(response.TestSessionObjID);

/*
			if($('#right #ModelAdminPanel form').hasClass('validationerror')) {
				statusMessage(ss.i18n._t('ModelAdmin.VALIDATIONERROR', 'Validation Error'), 'bad');
			} else {
				statusMessage(ss.i18n._t('ModelAdmin.SAVED', 'Lots Checked'), 'good');
			}
*/
			// TODO/SAM: It seems a bit of a hack to have to list all the little updaters here. 
			// Is livequery a solution?
			Behaviour.apply(); // refreshes ComplexTableField
		}, 'html');
		return false;
	});
	
	function statusMessage(msg, type){
		$('#statusmessage')[0].innerHTML = msg;
	}
	
		
	})
})(jQuery);

Behaviour.register({


	

	'div.passfail input' : {
		initialize : function() {
			this.step = this.parentNode;
			while(this.step.tagName.toLowerCase() != 'li') this.step = this.step.parentNode;
		},
		onclick : function() {
			this.step.setStatus(this.value);
		}
	},
	
	'ul.steps li' : {
		initialize : function() {
			this.statusInputs = [];

			var i,input,inputs = this.getElementsByTagName('input');
			for(i=0;input=inputs[i];i++) {
				this.statusInputs[input.value] = input;
			}
			
			var div,divs = this.getElementsByTagName('div');
			for(i=0;div=divs[i];i++) {
				//if(div.className == 'failReason') { this.failReason = div; break; }
				//if(div.className == 'passNote') { this.passNote = div; break; }
				if(div.className == 'note') { this.note = div; break; }
			}
		},
	
		setStatus : function(status) {
			this.className = status;
			
			var candStatus;
			for(candStatus in this.statusInputs) {
				if(candStatus == status) {
					Element.addClassName(this.statusInputs[candStatus].parentNode, 'selected');
				} else {
					Element.removeClassName(this.statusInputs[candStatus].parentNode, 'selected');
				}
			}
			//this.failReason.style.display = (status == 'pass') ? 'none' : '';
			this.note.style.display = '';
		}
	}
});