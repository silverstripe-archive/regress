var initialised;

// Make sure users aren't logged out when performe test window is open
setInterval(function() {
		new Ajax.Request("Security/ping");
}, 180*1000);

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

/**
 * When sucessMsg is displayed when ajax request is successful. 
 * It successMsg == '' the message from server side is used instead
 */ 
function saveDraft(successMsg) {
	var form = $('form[name=session]');
	var formAction = form.attr('action') + '?' + 'action_doSaveSession=Execute';

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
				if (successMsg == '') {
					statusMessage(response.Message, 'good');
				}
				else {
					var currentTime = new Date();
					var month = currentTime.getMonth();
					var day = currentTime.getDate();
					var year = currentTime.getFullYear();
					var hour = currentTime.getHours();
					var minute = currentTime.getMinutes();
					var second = currentTime.getSeconds();
					
					var timeStamp = '(' + year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second + ')'; 
					
					statusMessage(successMsg + ' ' + timeStamp, 'good');
				}
			}
			$('#TestSessionObjID').val(response.TestSessionObjID);
		}, 
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			errMsg = "ERROR";
			if(XMLHttpRequest.status == 403) {
				errMsg = 'Please login'; 
			}
			
			statusMessage(errMsg, 'bad');
		},
		dataTypeString: 'html'
	});
	return false;
}

(function($) {
$(document).ready(function() {
	
	$('form[name=session]').changeAwareForm();
	
	/**
	 * Auto save 
	 */ 
	setInterval(function() {
		// If one or more outcomes have been set
		if($(".passfail input[type=radio]:checked").length > 0) {
			saveDraft('Autosaved');
		}
	}, 180*500);
	
	/**
	 * Save draft
	 */
	initialised = false;
	$('input[name=action_doSaveSession]').livequery('click', function(){
		saveDraft('');
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
	
	// remove editable inputs
	$('.content').click(function() {
		id = $(this).attr('id');
		resetEditableInputs(id);
	});

	});
	
	/**
	 * Reset all jeditable form in all .content div, except the one whose id provided
	 * @param string
	 */
	function resetEditableInputs(exceptID) {
		contents = $('.content');
		for(i=0; i<contents.length; i++) {
			if ($(contents[i]).attr('id') == exceptID) {
				continue; 
			}

			if($.isFunction(contents[i].reset)) contents[i].reset();
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
	
	/**
	 * Check whether is there an outcome has been set before submitting the test plan
	 */
	$('form[name=session]').livequery('submit', function() {
		if($('input[name^=Outcome]:checked').length < 1) {
			alert("This test is still empty - please enter your test data first.");
			return false; 
		}
		
		ret = confirm("You are about to submit your test plan. Are you sure?");
		if(!ret) return false;
			
		return true;
	});
	
	/**
	 * Table of content (Index)
	 */
	buildTableOfContent();
	
	countTestSteps();
	
	$('a#hideIndex').livequery('click', function() {
		$('#tableOfContent').hide(); 
		return false;
	});
	
	$('a#showIndex').livequery('click', function() {
		$('#tableOfContent').show(); 
		return false;
	});
	
	function buildTableOfContent() {
		var html = '';
		var list = ''; 
		var location = window.location;
		var url = location.protocol + '//' + location.host + location.pathname;

		$('.anchor').each( function() { 
			list += '<li><a href="' + url + '#' + $(this).attr('id') + '">' + $(this).text() + '</a></li>'; 
		});
		
		topLink = location.href.replace(/#/g, '') + "#body";
	
		// add show table of content link to the leftPanel
		$('.leftPanel').prepend('<p class="showHideIndex"><a id="showIndex" href="#">Show Index</a></p>')
		
		html = '<div id="tableOfContent">';
		html += '<p class="showHideIndex"><a id="hideIndex" href="#">Hide Index</a></p>'
		html += '<h3>Index <a id="topLink" href="javascript:scroll(0,0)">(Go to top)</a></h3>';
		html += '<ul>' + list + '</ul></div>';
		$('body').append(html);
	}
	
	$('div.failseverity input').livequery('click', function() {
		var step = $(this).parents('li.scenario');
		step.addClass('fail').removeClass('pass skip');
		step.children('div.passfail').children('label.fail').children('input').attr('checked', true);
	});
	
	// change color of test-step div object
	$('div.passfail input').livequery('click', function() {
		var step = $(this).parents('li.scenario');
		step.removeClass('pass fail skip').addClass(this.value);
		step.children('div.failseverity').children().children('input').attr('checked',false);
	});	
	
	
	function countTestSteps() {
		var list = 0; 
		var location = window.location;

		$('li.scenario').each( function() { 
			list ++;
		});
		
		$('input[name=NumTestSteps]')[0].value = list;
	}	
})(jQuery);
