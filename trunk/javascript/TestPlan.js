var initialised;

(function($) {
$(document).ready(function() {
	
	initialised = false;
	$('input[name=action_doSaveSession]').livequery('click', function(){
		var form = $('form[@name=session]');
		var formAction = form.attr('action') + '?' + 'action_doSaveSession=Execute'

		statusMessage('', 'good');

		$.ajax({
			type: 'POST',
			url: formAction,
			data: form.formToArray(),
			success: function(result) {
				var response = eval('(' + result + ')');

				if (response.TestSessionObjID == '') {
					statusMessage(response.Message, 'bad');
				} else {
					statusMessage(response.Message, 'good');
				}
				$('#TestSessionObjID').val(response.TestSessionObjID);
			}, 
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				statusMessage("ERROR", 'bad');
			},
			dataTypeString: 'html'
		});
		return false;
	});

	/**
	 * Toggle the status of the perform test form so that tester can modify the
	 * scenarios during the test.
	 */
	$('input[name=action_doEditMode]').livequery('click', function(){
		var button = $('input[name=action_doEditMode]')[0]
		
		if (button.value == "Set to read-only mode") {
			// disable the editing
			$('.content').editable('disable', null);
			
			// add classname
			$('.content').removeClass('changeable').addClass('readonly');
			
			jQuery.each ($('.content'),function() {
				this.title = 'Scenario';
			});
			
			button.value = "Change to Edit Mode";
			statusMessage('Page is set to read-only mode.','good');
		} else {	
			// change to 'editable'
			if (initialised == false) {
				$('.content').editable('scenario/save', {
					loadurl   : 'scenario/load',
					loadtext  : 'Loading data from server...',
					type      : 'textarea',
			        indicator : 'Saving...',
			        tooltip   : 'Click to edit...',
			        cancel    : 'Cancel',
			        submit    : 'OK',
					rows      : 15,
					indicator : "<img src='images/indicator.gif",
				});
				button.value = "Set to read-only mode";
				initialised =true;
			} else {
				// disable the editing
				$('.content').editable('enable', null);
				button.value = "Set to read-only mode";				
			}

			// add classname
			$('.content').removeClass('readonly').addClass('changeable');

			statusMessage('<ul><li>To modify scenarios, click on the green sections of the page.</li><li>For more information about text-formating and markdown, <br/> please <a href="http://daringfireball.net/projects/markdown/syntax" target="_new">click here</a>.</li></ul>','good');
		}		
		return false;
	});

	
	function statusMessage(msg, type){
		
		if (type == 'good') {
			$('#statusmessage').removeClass('badMsg').addClass('goodMsg');
		} else 
		if (type == 'bad') {
			$('#statusmessage').removeClass('goodMsg').addClass('badMsg');
		} else {
			$('#statusmessage').removeClass('goodMsg badMsg');
		}
		$('#statusmessage')[0].innerHTML = msg;
	}
	});

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