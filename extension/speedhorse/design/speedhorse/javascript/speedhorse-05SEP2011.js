var jq = jQuery;
jQuery(document).ready(function($) {
								
								
	$("#navigation li").removeClass("current_page");
	
	 var loc = window.location;
    var mypath = loc.pathname;
	//alert(mypath);
	var containsFoo;
	
		
		 if(containsFoo = mypath.indexOf('RacingNews') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_2").addClass("current_page");
			$("#li_link_2").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_2").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else if(containsFoo = mypath.indexOf('Blogdetails') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_3").addClass("current_page");
			$("#li_link_3").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_3").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		else if(containsFoo = mypath.indexOf('Mediadetails') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_4").addClass("current_page");
			$("#li_link_4").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_4").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else if(containsFoo = mypath.indexOf('Calendardetails') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_5").addClass("current_page");
			$("#li_link_5").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_5").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else if(containsFoo = mypath.indexOf('Classifieddetails') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_6").addClass("current_page");
			$("#li_link_6").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_6").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else if(containsFoo = mypath.indexOf('Shopdetails') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_7").addClass("current_page");
			$("#li_link_7").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_7").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else if(containsFoo = mypath.indexOf('eMagazine') >= 0){
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_8").addClass("current_page");
			$("#li_link_8").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_8").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
		
		else{
			//alert("in if");
			$("#navigation li").removeClass("current_page");
			$("#li_link_1").addClass("current_page");
			$("#li_link_1").css({"background-image":"url(../images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			$("#li_link_1").find("a.main").css({"background-image":"url(../images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#0E4281"});
		}
	
	
  $("#calendar_items div.items").hide();
	 var theId1 = $("#months li a.active").attr('id');
	 //alert(theId);
	 if(theId1!== undefined){
	 $("#calendar_items #"+theId1+"-div").show();
	 }
	 $("#left_sidebar_content .info ul").hide();
	 $("#left_sidebar_content .active ul").show();
	 $("#left_sidebar_content ul ul.sub-ul").hide();
	 $("#left_sidebar_content li.active ul.sub-ul").show();
	  $("#class_cats ul.main ul.sub-cats").hide();
	 $("#class_cats li.active ul.sub-cats").show();
	 $('a[rel*=facebox]').facebox({
        loadingImage : 'http://collegeyardart.com/images/loading.gif',
        closeImage   : 'http://collegeyardart.com/images/closelabel.png'
      });
	var theId = $(".newmagazine").attr('id');
	if(theId!== undefined){
	// alert(theId);
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
	}
	 
	 
	   var myDate = new Date();
    var month = myDate.getMonth() + 1;
	//alert(month);
	if(month>7){
	var carousel = $("#left_arrow").parents('.carousel').find('ul');
	var scroll = carousel.scrollLeft();
	carousel.animate({ scrollLeft: scroll+549 }, 'fast');
	}
});


/* For emagazine section */


jQuery(function($){
	var imgCaps = $(".image_caption h3");
	$(".thumb_caption").each(function(i){
		this.innerHTML = imgCaps[i].innerHTML;
	});

	$("#gallery_container .the_images").each(function(){
		//if(){
			//alert($(this).id);
		$(this).width($(this).children(".thumb_link").length * 118);
		//}else{
			
		//}
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
			//$(this).css({"height":"33px"});
			//$(this).find("a.main").css({"height":"33px"});
			//$(this).find("div.dropdown").show();
			//$(this).css({"background-image":"url(../images/bg_left_hov.png)", "background-repeat":"no-repeat", "background-position":"left top"});
			//$(this).find("a.main").css({"background-image":"url(../images/bg_right_hov.png)", "background-repeat":"no-repeat", "background-position":"right top","color":"#ffffff"});
			//$(this).css({"height":"33px"});
			//$(this).find("a.main").css({"height":"33px"});
			
		}
		//$(this).addClass("drop_open");
		//$("#"+id).addClass("current_page");
		
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
		$(".the_images_video").removeClass("current_view");
		$(selectedLink).addClass("current_view");
		$(selectedClass).addClass("current_view");
	});

	$(".thumb_link").click(function(){
		var clicked = this;
		var id1=clicked.id;
		var containsFoo;
		if(containsFoo = id1.indexOf('thumb') >= 0)
		{
			//alert("in if");
			id1=id1.replace("thumb","media_item");
			//alert(id1);
			$(".media_item_preview").hide();
			//$("#"+id1).show();
			//alert($("#"+id1).html());
			alert($("#preview_pane").html);
			$("#preview_pane").text=$("#"+id1).html();
			$("#preview_pane").html($("#"+id1).html());
			$("#preview_pane").addClass("current_view");
			//$("#preview_pane").addClass("img");
			
		}else{
			//$(".media_item_preview").hide();
			//alert('in else');
			//alert($("#preview_pane").html());
			$("#preview_pane").html('<div class="preview_content top_stories"><img src="/var/ezflow_site/storage/images/featured-stories/feature-article33/1705-1-eng-US/Feature-Article33_large.png" style="border: 0px  ;" alt="Feature Article33" title="Feature Article33" width="80" height="80"><div class="large_image_caption_wrap"><div class="large_image_caption"><h3>Feature Article33</h3>Feature Article33</div><div class="more_link_wrap"><a href="/Featured-Stories/Feature-Article33" class="more_link">read more »</a></div></div></div><!-- photo --><div class="preview_content photos current_view"><img src="/var/ezflow_site/storage/images/media2/testimg1/2297-1-eng-US/testimg1_large.jpg" style="border: 0px  ;" alt="testimg1" title="testimg1" width="360" height="225"><div class="large_image_caption_wrap"><div class="large_image_caption"><h3><p>img1.</p></h3></div><div class="more_link_wrap"></div></div></div><!-- video --><div class="preview_content video"><img src="/var/ezflow_site/storage/images/videos/video1/5952-1-eng-US/Video1_large.jpg" style="border: 0px  ;" alt="Video1" title="Video1" width="195" height="144"><div class="large_image_caption_wrap"><div class="large_image_caption"><h3><p>Video1 Description</p></h3></div><div class="more_link_wrap"></div></div></div>');
		$(".current_view .thumb_frame").hide();
		$(clicked).find(".thumb_frame").show();
		//alert(clicked.getElementsByTagName("img")[0].getAttribute("src"));
		$(".current_view .large_image_caption_wrap").html($(clicked).children(".image_caption").html());
		$("#preview_pane .current_view img").attr("src", clicked.getElementsByTagName("img")[0].getAttribute("src"));
		}
	});

	$(".thumb_link_myvideo").click(function(){
		var clicked = this;
		//alert(clicked.id);
		$(".current_view .thumb_frame").hide();
		$(clicked).find(".thumb_frame").show();
		$(".current_view .large_image_caption_wrap").html($(clicked).children(".image_caption").html());
		var id1=clicked.id;
		//alert(id1);
		id1=id1.replace("thumb","big");
		alert(id1);
		$("#"+id1).show();
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
	
	$("#classify_images #images a").click(function(){
		$("#classify_images #main_img a").hide();
		var theId = $(this).attr('id');
		$("#classify_images #main_img a#"+theId+"-img").show();
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
		$(".subemagazine li").removeClass("eMagazineleftbarbold");
		 
		$("#"+theId+"_div").show();
		theId=theId.replace("aa","li")
		$("#"+theId).addClass("eMagazineleftbarbold");
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
		
		var theImageId1 = $(this).attr('id');
		var theSubul = $(this).attr('id');
		
		theImageId1=theImageId1.replace("ul", "subul");
	
		var id = $("ul#"+theImageId1+" li:first").attr("id");
		var id1 = $("ul#"+theImageId1+" li:first").attr("id");
		$(".mini_nav a").removeClass("emagazineactive");
		var id2=id.replace("li","aa");
		$("#"+id2).addClass("emagazineactive");
		id=id.replace("li","aa")+"_div";
		id1=id1.replace("aa","li");
		$("#"+id).show();
		$(".subemagazine li").removeClass("eMagazineleftbarbold");
		$("#"+id1).addClass("eMagazineleftbarbold");
		
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
	$(".mini_nav a").removeClass("emagazineactive");
		var id2=id.replace("li","aa");
		$("#"+id2).addClass("emagazineactive");
	id=id.replace("li","aa")+"_div";
	id1=id1.replace("aa","li");
	$("#"+id).show();
	$(".subemagazine li").removeClass("eMagazineleftbarbold");
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
	

	$('#media_wrap .basic').click(function (e) {
		var theIdli = $(this).attr('id');
		//alert(theIdli);
		theIdli=theIdli.replace("aa_","");
		//alert(theIdli);
		$('#'+theIdli).modal();

		return false;
	});	
	
		$('#calendar_items .gomap').click(function (e) {
		 $(".div-gomap").hide();
		var theIdli = $(this).attr('id');
		alert(theIdli);
		theIdli=theIdli.replace("aa_","");
		alert(theIdli);
		$('#'+theIdli).show();

		return false;
	});	
	
	$("#class_cats li a.main").toggle(function(){
			 $(this).parent().find("ul.sub-cats").show();
			 $(this).parent().addClass("active");
		},
		function(){
			 $(this).parent().find("ul.sub-cats").hide();
			 $(this).parent().removeClass("active");
		}
	);
	
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
      loadingImage : 'http://collegeyardart.com/images/loading.gif',
      closeImage   : 'http://collegeyardart.com/images/closelabel.png',
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


//for hiding and unhiding gmap

var slideDownInitHeight = new Array();
	var slidedown_direction = new Array();

	var slidedownActive = false;
	var contentHeight = false;
	var slidedownSpeed = 3; 	// Higher value = faster script
	var slidedownTimer = 7;	// Lower value = faster script
	function slidedown_showHide(boxId)
	{
		if(!slidedown_direction[boxId])slidedown_direction[boxId] = 1;
		if(!slideDownInitHeight[boxId])slideDownInitHeight[boxId] = 0;
		
		if(slideDownInitHeight[boxId]==0)slidedown_direction[boxId]=slidedownSpeed; else slidedown_direction[boxId] = slidedownSpeed*-1;
		
		slidedownContentBox = document.getElementById(boxId);
		var subDivs = slidedownContentBox.getElementsByTagName('DIV');
		for(var no=0;no<subDivs.length;no++){
			if(subDivs[no].className=='dhtmlgoodies_content')slidedownContent = subDivs[no];	
		}

		contentHeight = slidedownContent.offsetHeight;
	
		slidedownContentBox.style.visibility='visible';
		slidedownActive = true;
		slidedown_showHide_start(slidedownContentBox,slidedownContent);
	}
	function slidedown_showHide_start(slidedownContentBox,slidedownContent)
	{

		if(!slidedownActive)return;
		slideDownInitHeight[slidedownContentBox.id] = slideDownInitHeight[slidedownContentBox.id]/1 + slidedown_direction[slidedownContentBox.id];
		if(slideDownInitHeight[slidedownContentBox.id] <= 0){
			slidedownActive = false;	
			slidedownContentBox.style.visibility='hidden';
			slideDownInitHeight[slidedownContentBox.id] = 0;
		}
		if(slideDownInitHeight[slidedownContentBox.id]>contentHeight){
			slidedownActive = false;	
		}
		slidedownContentBox.style.height = slideDownInitHeight[slidedownContentBox.id] + 'px';
		slidedownContent.style.top = slideDownInitHeight[slidedownContentBox.id] - contentHeight + 'px';

		setTimeout('slidedown_showHide_start(document.getElementById("' + slidedownContentBox.id + '"),document.getElementById("' + slidedownContent.id + '"))',slidedownTimer);	// Choose a lower value than 10 to make the script move faster
	}
	
	function setSlideDownSpeed(newSpeed)
	{
		slidedownSpeed = newSpeed;
		
	}
	
	
	function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
