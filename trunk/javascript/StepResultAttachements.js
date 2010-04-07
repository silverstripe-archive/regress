(function($) {
	var filePrefix = "ajaxupload";
	var iframeID = "ajaxuploadiframe";
	var currentFileID = null;
	
	$(document).ready(function() {
		// add iframe for uploading files with out leaving the page
		$('body').append('<iframe src="javascript:false;" id="' + iframeID + '" name="' + iframeID + '" style="position:absolute; z-index:100; top:10px; right: 10px; float:right; display: none" />');
	});
	
	$("input[type=file].ajaxupload").change(function() {
		var form = $(this).parents('form')[0];
		var action = "session/uploadattachment/423";
		var container = $(this).parent(); 
		
		
		$(form).attr('target', iframeID);
		
		// set action to upload file 
		var oldAction = $(form).attr('action');
		$(form).attr('action', action);
		
		currentFileID = $(this).attr('id').replace(filePrefix + '-', '');
		form.submit();
		
		// disable all file input fiels while uploading 
		$("input[type=file].ajaxupload").attr('disabled', 'disabled');
		container.find('label').text('Uploading...');

		// revert to old action url
		$(form).attr('action', oldAction);
		$(form).attr('target', '');
		
	});
	
	/**
	 * Event handler for iframe on load
	 */
	$('iframe').livequery('load', function() {
		// revert the label 
		$('.noteAttachments label').text('Attachments');
		
		$("input[type=file].ajaxupload").attr('disabled', false);
		// clear file input 
		$("input[type=file].ajaxupload").val('');
		
		// process the content of iframe
		var iframeBody = $('#' + iframeID).contents().find('body');
		var response = iframeBody.find('ul#response');
		if(response.size() > 0) {
			var response = response[0];
			var status = $(response).find('li.status')[0];
			var sessionID = $(response).find('li.sessionid')[0];
			var message = $(response).find('li.message')[0];
			var fileid = $(response).find('li.fileid')[0];
			var filename = $(response).find('li.filename')[0];
			var url = $(response).find('li.url')[0];
			
			// uploaded successfully
			if($.trim($(status).text()) == "success" && ($(filename).text() != '' && $(url).text() != '')) {
				var attachmentList = $("#note-" + currentFileID + " ul.attachmentList");
				$(attachmentList).append('<li class="file-' + $(fileid).text() + '"><a href="' + $(url).text() + '">' + $(filename).text() + '</a> <a class="removeFile" href="admin/assets/removefile/' + $(fileid).text() + '">Delete</a></li>');
				
				$('#TestSessionObjID').val($(sessionID).text());
				
				statusMessage('<strong>' + $(filename).text() + '</strong> has been uploaded successfully.', 'good');
			}
			else {
				alert('dfds');
				statusMessage($(message).text(), 'bad');
			} 
		}
	});

	/**
	 * Event handler for delete links
	 */
	$('a.removeFile').livequery('click', function() {
		var url = $(this).attr('href');
		var linkContainer = $(this).parent('li');
		
		var filename = $(linkContainer.children('a')[0]).text();
		var confirm = window.confirm('Are you sure you want to delete "'+ filename +'"?');
		if(!confirm) return false;
		
		$.ajax({ 
			url: url, 
			success: function(){
		        linkContainer.remove();
			}
		});
		
		return false;
	});
	
})(jQuery);