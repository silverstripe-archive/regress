var initialised;

(function($) {
$(document).ready(function() {
	
	$('form[name=session]').changeAwareForm();
	
	initialised = false;
	$('input[name=action_doSaveSession]').livequery('click', function(){
		var form = $('form[name=session]');
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
	$('a.editModeLink').livequery('click', function(){
		
		var editModeClass = 'inEditMode';
		
		// the link/page is in edit mode, change to readonly mode 
		if ($(this).hasClass(editModeClass)) {
			
			// disable the editing
			$('.content').editable('disable', null);
			
			// remove editable inputs
			resetEditableInputs();
			
			// add classname
			$('.content').removeClass('changeable').addClass('readonly');
			
			jQuery.each ($('.content'),function() {
				this.title = 'Scenario';
			});
			
			$(this).text("Change to Edit Mode");
			statusMessage('Page is set to read-only mode.','good');
			
			// change the link class
			$(this).toggleClass(editModeClass);
			
		} else { // change to editable mode
			
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
					width	  : '99%',
					onblur	  : 'ignore',
					indicator : "<img src='images/indicator.gif"
				});
				
				$(this).text("Set to read-only mode");
				initialised =true;
				
			} else {
				// disable the editing
				$('.content').editable('enable', null);
				$(this).text("Set to read-only mode");				
			}
			
			// add classname
			$('.content').removeClass('readonly').addClass('changeable');

			statusMessage('<ul><li>To modify scenarios, click on the green sections of the page.</li><li>For more information about text-formatting and markdown, <br/> please <a href="http://daringfireball.net/projects/markdown/syntax" target="_new">click here</a>.</li></ul>','good');
			
			// change the link class
			$(this).toggleClass(editModeClass);
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
	
	function resetEditableInputs() {
		contents = $('.content');
		for(i=0; i<contents.length; i++) {
			contents[i].reset();
		}
	}
	
	/**
	 * Mock submit test button 
	 * When clicked, it will instead submit the main form
	 */
	$('input[name=action_mockSubmitTest]').livequery('click', function() {
		var mainForm = $('form[name=session]');
		mainForm.submit(); 
		
		return false; 
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