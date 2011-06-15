/**
* Bits on the Run video uploader; displays upload progress for regular HTML forms.
* Built on top of jQuery and jQuery uploadProgress.
**/
$(document).ready(function() {
	// Push an empty div after the file input box. It'll display upload progress.
	$('.uploadButton').after(
		'<div class="uploadBar" style="width:50%; float:left; display:none; background:#FFF; margin:5px 0;">' +
		'<div class="uploadProgress" style="background:#46800d; width:0px; height:18px;"></div></div>');		
});

function botr_add_upoload_progress(botr_active_at_id){
	// Attach an uploadProgress instance to the form. This tool will poll the server for progress.
	$("#" + botr_active_at_id + " .uploadText").addClass('active');
	$('#uploadForm').uploadProgress({
		// The javascript paths are needed because uploadProgress builds an iframe that sits on top of the page.
		jqueryPath: "http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js",
		uploadProgressPath: "/extension/multimedia/design/standard/javascript/jquery.uploadProgress.js",
		// The uploadProgress bar had just been inserted into the form.
		progressBar: "#" + botr_active_at_id + ' .uploadProgress',
		// This is the BOTR callback for upload progress.
		progressUrl: 'http://upload.bitsontherun.com/progress',
		// The token is needed to request fallback. It is pulled from the form.
		uploadToken: $('#uploadToken').val(),
		interval:1000,
		complete: function(upload) {
//			$("#" + botr_active_at_id + " .fileDetails").html("<p>You're video has been uploaded. Translation details will appear shortly.</p>");
		},
		error: function(upload) {
//			alert("Upload failed! Please discard this draft and try again!");
		},
		// When the upload starts, we hide the input, show the progress and disable the button.
		start: function() {
			filename = $("#uploadFile").val().split(/[\/\\]/).pop();
			$("#" + botr_active_at_id + " .uploadBar").css('display','block');
			$("#" + botr_active_at_id + " .uploadButton").attr('disabled','disabled');
		},
		// During upload, we update both the progress div and the text below it.
		uploading: function(upload) {
			$(".uploadText.active").html('Uploading ' + filename + ' (' + upload.percents + '%) ...');
		}
	});
}