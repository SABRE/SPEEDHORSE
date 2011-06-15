$(function() {
	if($.browser.safari && top.document == document) {
	  /* iframe to send ajax requests in safari
	   thanks to Michele Finotto for idea */
	  iframe = document.createElement('iframe');
	  iframe.name = "progressFrame";
	  $(iframe).css({width: '0', height: '0', position: 'absolute', top: '-3000px'});
	  document.body.appendChild(iframe);

	  var d = iframe.contentWindow.document;
	  d.open();
	  /* weird - safari won't load scripts without this lines... */
	  d.write('<html><head></head><body></body></html>');
	  d.close();

	  var b = d.body;
	
	  var s = d.createElement('script');
	  s.src = "/extension/ezjscore/design/standard/javascript/jquery-1.4.2.min.js";
	  b.appendChild(s);
      var s1 = d.createElement('script');
      s1.src = "/extension/multimedia/design/standard/javascript/jquery.uploadProgress.js";
      b.appendChild(s1);
	}
});

/*
* jquery.uploadProgress
*
* Copyright (c) 2008 Piotr Sarnacki (drogomir.com)
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/mit-license.php
*
* Source:
* http://github.com/drogus/jquery-upload-progress/blob/a518b05fa85b1833bb3a8e8c2a0c8dfb9d6abaf1/jquery.uploadProgress.js
*
* 2009-11-05 Sergey Lashin (www.bitsontherun.com)
* - Updated to work with Bits on the Run upload server
*/
(function($) {
  $.fn.uploadProgress = function(options) {
  options = $.extend({
    dataType: "jsonp",
    interval: 2000,
    progressBar: "#progressbar",
    progressUrl: "/progress",
    uploadToken: "",
    start: function() {},
    uploading: function() {},
    complete: function() {},
    success: function() {},
    error: function() {},
    preloadImages: [],
    jqueryPath: '/extension/ezjscore/design/standard/javascript/jquery-1.4.2.min.js',
    uploadProgressPath: '/extension/multimedia/design/standard/javascript/jquery.uploadProgress.js',
    timer: ""
  }, options);

  $(function() {
    //preload images
    for(var i = 0; i<options.preloadImages.length; i++)
    {
     options.preloadImages[i] = $("<img>").attr("src", options.preloadImages[i]);
    }
  });

  return this.each(function(){
    $(this).bind('submit', function() {
      /* start callback */
      options.start();

      var uploadProgress = $.browser.safari ? progressFrame.jQuery.uploadProgress : jQuery.uploadProgress;
      options.timer = window.setInterval(function() { uploadProgress(this, options) }, options.interval);
    });
  });
  };

jQuery.uploadProgress = function(e, options) {
  jQuery.ajax({
    type: "GET",
    url: options.progressUrl,
    data: "token=" + options.uploadToken,
    dataType: options.dataType,
    success: function(upload) {
	  proc = 0;
      if (upload.state == 'uploading') {
        upload.percents = Math.floor((upload.received / upload.size)*1000)/10;

        var barN = $.browser.safari ? $(options.progressBar, parent.document) : $(options.progressBar);
        barN.css({width: upload.percents+'%'});
        options.uploading(upload);
		proc=1;
      }

      if (upload.state == 'done' || upload.state == 'error') {
        window.clearInterval(options.timer);
        options.complete(upload);
		proc=1;
      }

      if (upload.state == 'done') {
        options.success(upload);
		proc=1;
      }

      if (upload.state == 'error') {
        options.error(upload);
		proc=1;
      }
	  
	  if (upload == '' || upload.state == 'starting') {
		proc=1;
      }
	  
	  if (proc==0) alert("Sorry - An unknown error occured! Please discard this ez draft and try again!");
    }
  });
};

})(jQuery);
