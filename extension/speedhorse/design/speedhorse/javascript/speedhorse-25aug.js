var jq = jQuery;
jQuery(document).ready(function($) {
  $("#calendar_items div.items").hide();
	 var theId = $("#months li a.active").attr('id');
	 $("#calendar_items #"+theId+"-div").show();
	 
	 $("#left_sidebar_content .info ul").hide();
	 $("#left_sidebar_content .active ul").show();
	 $("#left_sidebar_content ul ul.sub-ul").hide();
	 $("#left_sidebar_content li.active ul.sub-ul").show();
	 $('a[rel*=facebox]').facebox({
        loadingImage : 'http://sandbox.speedhorse.com/images/loading.gif',
        closeImage   : 'http://sandbox.speedhorse.com/images/closelabel.png'
      })
});


/* For emagazine section */

jQuery(document).ready(function($) {
	//		alert('ggggg');
 // $("#magazine_items div.items").hide();
	 var theId = $(".newmagazine").attr('id');
	 //alert(theId);
	 $(".newmagazine").hide();
	 $("#"+theId).show();
	 var theImageId = $(".emagazineactiveimg").attr('id');
	 $(".items").hide();
	 $("#"+theImageId).show();
	 
	 $("#curremagazine_wrap ul.subemagazine").hide();
	 $("#curremagazine_wrap ul.subemagazineactive").show();
	 
	 $("#curremagazine_wrap li.subemagazineli").show();
	
	var id = $("ul#"+theImageId+" li:first").attr("id");
		
		var theIdul = $(".newmagazine").attr('id');
		//alert(theIdul);
		if(theIdul!== undefined){
		theIdul = theIdul.replace("big","subul");
		id = $("ul#"+theIdul+" li:first").attr("id");
		id=id.replace("aa","li");
		//alert(id);
		$("#"+id).addClass("eMagazineleftbarbold");
		}
	// $("#left_sidebar_content .info ul").hide();
	// $("#left_sidebar_content .active ul").show();
	// $("#left_sidebar_content ul ul.sub-ul").hide();
	// $("#left_sidebar_content li.active ul.sub-ul").show();
});

/* For emagazine left section 

jQuery(document).ready(function($) {
	//		alert('ggggg');
  //$("#magazine_items div.items").hide();
	// var theId = $("#small_nav a.active").attr('id');
	 
	// $("#magazine_items #"+theId+"_div").show();
	 
	// $("#left_sidebar_content .info ul").hide();
	// $("#left_sidebar_content .active ul").show();
	// $("#left_sidebar_content ul ul.sub-ul").hide();
	// $("#left_sidebar_content li.active ul.sub-ul").show();
});

*/
/* End of e magazine section */

jQuery(function($){
	var imgCaps = $(".image_caption h3");
	$(".thumb_caption").each(function(i){
		this.innerHTML = imgCaps[i].innerHTML;
	});

	$("#gallery_container .the_images").each(function(){
		$(this).width($(this).children(".thumb_link").length * 118);
	});

	$("#navigation li.main").hover(function(e){ //.not(".current_page")
		$(this).css({"background-image":"url(../images/bg_left_hov.png)", "background-repeat":"no-repeat", "background-position":"left top"});
		$(this).find("a.main").css({"background-image":"url(../images/bg_right_hov.png)", "background-repeat":"no-repeat", "background-position":"right top"});/* , "color":"#23436a" */
		if ( $(this).hasClass("current_page") ) {
			$(this).css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$(this).find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		if ( $(this).children().size() > 1 && $(this).hasClass("current_page") ) {
			$(this).css({"background-image":"url(../images/bg_left_hov.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$(this).find("a.main").css({"background-image":"url(../images/bg_right_hov.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#ffffff"});/* , "color":"#23436a" */
		}
	}, function(e){
			if ( $(this).not(".drop_open") ) {
				$(this).css({"background":"none"});
				$(this).find("a.main").css({"background":"none", "color":"white"});
				if ( $(this).hasClass("current_page") ) {
					$(this).css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
					$(this).find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
				}
			}
			if ( $(this).find("div.dropdown").css("display") == "block" ) {
				$(this).find("div.dropdown").hide();
				
				if ( $(this).hasClass("current_page") ) {
					$(this).css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
					$(this).find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
				}
				if ( $(this).children().size() > 1 ) {
					$(this).css({"height":"27px"});
					$(this).find("a.main").css({"height":"27px"});
					$(this).find("div.dropdown").hide();
					$(this).removeClass("drop_open");
				}
			}
	});
	$("#navigation li.main").click(function(){
		
		if ( $(this).children().size() > 1 ) {
			$(this).css({"height":"33px"});
			$(this).find("a.main").css({"height":"33px"});
			$(this).find("div.dropdown").show();
			$(this).css({"background-image":"url(../images/bg_left_hov.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$(this).find("a.main").css({"background-image":"url(../images/bg_right_hov.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#ffffff"});
			$(this).css({"height":"33px"});
			$(this).find("a.main").css({"height":"33px"});
		}
		$(this).addClass("drop_open");
	});
	/*$("#navigation li.main").toggle(function(){
		
		if ( $(this).children().size() > 1 ) {
			$(this).css({"height":"33px"});
			$(this).find("a.main").css({"height":"33px"});
			$(this).find("div.dropdown").show();
			$(this).css({"background-image":"url(../images/bg_left_hov.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$(this).find("a.main").css({"background-image":"url(../images/bg_right_hov.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#ffffff"});
			$(this).css({"height":"33px"});
			$(this).find("a.main").css({"height":"33px"});
		}
		$(this).addClass("drop_open");
	}, function(){
			if ( $(this).hasClass("current_page") ) {
				$(this).css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
				$(this).find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
				$(this).css({"height":"27px"});
				$(this).find("a.main").css({"height":"27px"});
			}
			if ( $(this).children().size() > 1 ) {
				$(this).css({"height":"27px"});
				$(this).find("a.main").css({"height":"27px"});
				$(this).find("div.dropdown").hide();
				$(this).removeClass("drop_open");
			}
	});*/
	/*jq(document).click( function(event) {
			jq("#navigation li.main div.dropdown").hide();
			
			if ( $("#navigation li.main div.dropdown").parent().hasClass("current_page") ) {
				$("#navigation li.main div.dropdown").parent().css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
				$("#navigation li.main div.dropdown").parent().find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
			}
			if ( $("#navigation li.main div.dropdown").parent().children().size() > 1 ) {
				$("#navigation li.main div.dropdown").parent().css({"height":"27px"});
				$("#navigation li.main div.dropdown").parent().find("a.main").css({"height":"27px"});
				$("#navigation li.main div.dropdown").parent().find("div.dropdown").hide();
				$("#navigation li.main div.dropdown").parent().removeClass("drop_open");
			}
	});*/

	$("#accordion").accordion({ fillSpace: true	});
	$("#results_tabs").tabs();
	$("#leaderboards_tabs").tabs();
	$("#sales_tabs").tabs();
	$("#videos_tabs").tabs();
	$("#curremagazine_tabs").tabs();
	$("#archivedemagazine_tabs").tabs();

	$("#slideshow_menu a").click(function(){
		var selectedLink = this;
		var selectedClass = "." + $(this).attr("id");
		$(".preview_content").removeClass("current_view");
		$("#slideshow_menu a").removeClass("current_view");
		$(".the_images").removeClass("current_view");
		$(selectedLink).addClass("current_view");
		$(selectedClass).addClass("current_view");
	});

	$(".thumb_link").click(function(){
		var clicked = this;
		$(".current_view .thumb_frame").hide();
		$(clicked).find(".thumb_frame").show();
		$(".current_view .large_image_caption_wrap").html($(clicked).children(".image_caption").html());
		$("#preview_pane .current_view img").attr("src", clicked.getElementsByTagName("img")[0].getAttribute("data-fullsize"));
	});

	$("#arrow_left").click(function(){
		var carousel = $("#gallery_container .current_view");
		var hiddenWidth = carousel.width() - 599;
		var leftOffset = parseInt(carousel.css("left"));
		if(leftOffset < -472){
			carousel.css("left", leftOffset + 472);
		} else {
			carousel.css("left", 0);
		}
	});

	$("#arrow_right").click(function(){
		var carousel = $("#gallery_container .current_view");
		var hiddenWidth = carousel.width() - 599;
		var leftOffset = parseInt(carousel.css("left"));
		if(hiddenWidth + leftOffset > 472){
			carousel.css("left", leftOffset - 472);
		} else {
			carousel.css("left", -hiddenWidth);
		}
	});
	$("#product_images #images a").click(function(){
		$("#product_images #main_img a").hide();
		var theId = $(this).attr('id');
		$("#product_images #main_img a#"+theId+"-img").show();
		return false;
	});
	$("#left_sidebar_content .info h3").toggle(function(){
			 $(this).parent().find("ul").show();
		},
		function(){
			 $(this).parent().find("ul").hide();
		}
	);
	
	$("#left_sidebar_content .info h9").toggle(function(){
			 $(this).parent().find("ul").show();
		},
		function(){
			 $(this).parent().find("ul").hide();
		}
	);
	$("#left_sidebar_content .info ul li a").toggle(function(){
			 $(this).parent().find("ul").show();
		},
		function(){
			 $(this).parent().find("ul").hide();
		}
	);
	
	$("#blog_archives li a").toggle(function(){
			 $(this).parent().find("ul.archives_year").show();
			 $(this).parent().addClass("open");
		},
		function(){
			 $(this).parent().find("ul.archives_year").hide();
			 $(this).parent().removeClass("open");
		}
	);
	
	$("#blog_archives .archives_year li a").toggle(function(){
			 $(this).parent().find("ul.archives_month").show();
			 $(this).parent().addClass("open");
		},
		function(){
			 $(this).parent().find("ul.archives_month").hide();
			 $(this).parent().removeClass("open");
		}
	);
	$("#months li a").click(function(){
		 $("#months li a").removeClass("active");
		 $(this).addClass("active");
		 $("#calendar_items div.items").hide();
		 var theId = $(this).attr('id');
		 $("#calendar_items #"+theId+"-div").show();
		 return false;
	});
	$("#main_img a, #media .media_item").click(function(){
		$("#darken").show();
		$("#lightbox-wrap").show();
		 return false;
	});
	$("#close_lighbox").click(function(){		  
		$("#darken").hide();
		$("#lightbox-wrap").hide();
		 return false;
	});
	$("#mobile-nav a").click(function(){
		 $("#mobile-nav a").parent().removeClass("active");
		 $(this).parent().addClass("active");
		 $(".content").hide();
		 var theId = $(this).parent().attr('id');
		 $("#"+theId+"_content").show();
		 return false;
	});
	$("a#select-a-section").toggle(function(){
		 $("#select-a-section-drop").show();
	},
	function(){
		 $("#select-a-section-drop").hide();
	});
	$("a#select-a-section-a").toggle(function(){
		 $("#select-a-section-drop-a").show();
	},
	function(){
		 $("#select-a-section-drop-a").hide();
	});
	$(".mini_nav a").click(function(){
		//alert('helo');
		 $(".mini_nav a").removeClass("emagazineactive");
		 $(this).addClass("emagazineactive");
		//$("#magazine_items div.items").hide();
		$(".items").hide();
		 var theId = $(this).attr('id');
		// alert(theId);
		$("#"+theId+"_div").show();
		 return false;
	});
	
	$(".leftbaractive").click(function(){
		
		var theId = $(this).attr('id');
		 //alert(theId.replace("ul", "big"));
		 theId=theId.replace("ul", "big");
		 $(".newmagazine").hide();
		 //alert(theId);
		$("#"+theId).show();
		
		var theId = $(".newmagazine").attr('id');
	// alert(theId);
	 $(".newmagazine").hide();
	 $("#"+theId).show();
	 var theImageId = $(".emagazineactiveimg").attr('id');
	 $(".items").hide();
	 $("#"+theImageId).show();
	 
	 $("#curremagazine_wrap ul.subemagazine").hide();
	 $("#curremagazine_wrap ul.subemagazineactive").show();
	 
	 $("#curremagazine_wrap li.subemagazineli").show();
		$("#curremagazine_wrap a").removeClass("leftbaractive");
		$(this).addClass("leftbaractive");
		
		 return false;
	});
	
	$(".leftbarinactive").click(function(){
		//alert('hellllllo');
		//value.replace(".", ":");
		 $("#curremagazine_wrap a").removeClass("leftbaractive");
		 
		//$("#magazine_items div.items").hide();
		//$(".items").hide();
		var theId = $(this).attr('id');
		var theImageId = $(this).attr('id');
		var theSubul = $(this).attr('id');
		
		 //alert(theId.replace("ul", "big"));
		 theId=theId.replace("ul", "big");
		 $(".newmagazine").hide();
		//alert(theImageId);
		$("#"+theId).show();
	 $(".items").hide();
	
	theImageId=theImageId.replace("ul", "subul");
	
	var id = $("ul#"+theImageId+" li:first").attr("id");
	var id1 = $("ul#"+theImageId+" li:first").attr("id");
	id=id.replace("li","aa")+"_div";
	id1=id1.replace("aa","li");
	$("#"+id).show();
	
	$("#"+id1).addClass("eMagazineleftbarbold");
	
		//alert(id);
		$("#curremagazine_wrap ul.subemagazine").hide();
		theSubul=theSubul.replace("ul","subul");
	//alert(theSubul);
	  $("#"+theSubul).show();
	 
	 $(this).addClass("leftbaractive");
	 	
		 return false;
	});
	
	
	


$(".subemagazineli").click(function(){
		var theId = $(this).attr('id');
		var theIdli = $(this).attr('id');
		//alert(theId);
		$("#curremagazine_wrap li").removeClass("eMagazineleftbarbold");
	 	$(".items").hide();
		theId=theId.replace("li","aa")+"_div";
		theIdli=theIdli.replace("li","aa");
		
		$(".mini_nav a").removeClass("emagazineactive");
		$("#"+theIdli).addClass("emagazineactive");
		$("#"+theId).show();
		$(this).addClass("eMagazineleftbarbold");
		 return false;
	});
	
	
	
});

(function($) {
  $.facebox = function(data, klass) {
    $.facebox.loading()

    if (data.ajax) fillFaceboxFromAjax(data.ajax, klass)
    else if (data.image) fillFaceboxFromImage(data.image, klass)
    else if (data.div) fillFaceboxFromHref(data.div, klass)
    else if ($.isFunction(data)) data.call($)
    else $.facebox.reveal(data, klass)
  }

  /*
   * Public, $.facebox methods
   */

  $.extend($.facebox, {
    settings: {
      opacity      : 0.2,
      overlay      : true,
      loadingImage : 'http://sandbox.speedhorse.com/images/loading.gif',
      closeImage   : 'http://sandbox.speedhorse.com/images/closelabel.png',
      imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
      faceboxHtml  : '\
    <div id="facebox" style="display:none;"> \
      <div class="popup"> \
        <div class="content"> \
        </div> \
        <a href="#" class="close"></a> \
      </div> \
    </div>'
    },

    loading: function() {
      init()
      if ($('#facebox .loading').length == 1) return true
      showOverlay()

      $('#facebox .content').empty().
        append('<div class="loading"><img src="'+$.facebox.settings.loadingImage+'"/></div>')

      $('#facebox').show().css({
        top:	getPageScroll()[1] + (getPageHeight() / 10),
        left:	$(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2)
      })

      $(document).bind('keydown.facebox', function(e) {
        if (e.keyCode == 27) $.facebox.close()
        return true
      })
      $(document).trigger('loading.facebox')
    },

    reveal: function(data, klass) {
      $(document).trigger('beforeReveal.facebox')
      if (klass) $('#facebox .content').addClass(klass)
      $('#facebox .content').empty().append(data)
      $('#facebox .popup').children().fadeIn('normal')
      $('#facebox').css('left', $(window).width() / 2 - ($('#facebox .popup').outerWidth() / 2))
      $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
    },

    close: function() {
      $(document).trigger('close.facebox')
      return false
    }
  })

  /*
   * Public, $.fn methods
   */

  $.fn.facebox = function(settings) {
    if ($(this).length == 0) return

    init(settings)

    function clickHandler() {
      $.facebox.loading(true)

      // support for rel="facebox.inline_popup" syntax, to add a class
      // also supports deprecated "facebox[.inline_popup]" syntax
      var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
      if (klass) klass = klass[1]

      fillFaceboxFromHref(this.href, klass)
      return false
    }

    return this.bind('click.facebox', clickHandler)
  }

  /*
   * Private methods
   */

  // called one time to setup facebox on this page
  function init(settings) {
    if ($.facebox.settings.inited) return true
    else $.facebox.settings.inited = true

    $(document).trigger('init.facebox')
    makeCompatible()

    var imageTypes = $.facebox.settings.imageTypes.join('|')
    $.facebox.settings.imageTypesRegexp = new RegExp('\\.(' + imageTypes + ')(\\?.*)?$', 'i')

    if (settings) $.extend($.facebox.settings, settings)
    $('body').append($.facebox.settings.faceboxHtml)

    var preload = [ new Image(), new Image() ]
    preload[0].src = $.facebox.settings.closeImage
    preload[1].src = $.facebox.settings.loadingImage

    $('#facebox').find('.b:first, .bl').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })

    $('#facebox .close')
      .click($.facebox.close)
      .append('<img src="'
              + $.facebox.settings.closeImage
              + '" class="close_image" title="close">')
  }

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;
    }
    return new Array(xScroll,yScroll)
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }
    return windowHeight
  }

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.facebox.settings

    $s.loadingImage = $s.loading_image || $s.loadingImage
    $s.closeImage = $s.close_image || $s.closeImage
    $s.imageTypes = $s.image_types || $s.imageTypes
    $s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  //     div: #id
  //   image: blah.extension
  //    ajax: anything else
  function fillFaceboxFromHref(href, klass) {
    // div
    if (href.match(/#/)) {
		
      var url    = window.location.href.split('#')[0]
      var target = href.replace(url,'')
      if (target == '#') return
      $.facebox.reveal($(target).html(), klass)

    // image
    } else if (href.match($.facebox.settings.imageTypesRegexp)) {
      fillFaceboxFromImage(href, klass)
    // ajax
    } else {
      fillFaceboxFromAjax(href, klass)
    }
  }

  function fillFaceboxFromImage(href, klass) {
    var image = new Image()
    image.onload = function() {
      $.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
    }
    image.src = href
  }

  function fillFaceboxFromAjax(href, klass) {
    $.get(href, function(data) { $.facebox.reveal(data, klass) })
  }

  function skipOverlay() {
    return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
  }

  function showOverlay() {
    if (skipOverlay()) return

    if ($('#facebox_overlay').length == 0)
      $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

    $('#facebox_overlay').hide().addClass("facebox_overlayBG")
      .css('opacity', $.facebox.settings.opacity)
      .click(function() { $(document).trigger('close.facebox') })
      .fadeIn(200)
    return false
  }

  function hideOverlay() {
    if (skipOverlay()) return

    $('#facebox_overlay').fadeOut(200, function(){
      $("#facebox_overlay").removeClass("facebox_overlayBG")
      $("#facebox_overlay").addClass("facebox_hide")
      $("#facebox_overlay").remove()
    })

    return false
  }

  /*
   * Bindings
   */

  $(document).bind('close.facebox', function() {
    $(document).unbind('keydown.facebox')
    $('#facebox').fadeOut(function() {
      $('#facebox .content').removeClass().addClass('content')
      $('#facebox .loading').remove()
      $(document).trigger('afterClose.facebox')
    })
    hideOverlay()
  })

})(jQuery);
