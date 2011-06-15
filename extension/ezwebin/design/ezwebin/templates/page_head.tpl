{default enable_help=true() enable_link=true() canonical_link=true()}

{if is_set($module_result.content_info.persistent_variable.site_title)}
    {set scope=root site_title=$module_result.content_info.persistent_variable.site_title}
{else}
{let name=Path
     path=$module_result.path
     reverse_path=array()}
  {if is_set($pagedata.path_array)}
    {set path=$pagedata.path_array}
  {elseif is_set($module_result.title_path)}
    {set path=$module_result.title_path}
  {/if}
  {section loop=$:path}
    {set reverse_path=$:reverse_path|array_prepend($:item)}
  {/section}

{set-block scope=root variable=site_title}
{section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section} - {$site.title|wash}
{/set-block}

{/let}
{/if}
    <title>{$site_title}</title>

    {if and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
      {section name=ExtraData loop=$#Header:extra_data}
      {$:item}
      {/section}
    {/if}

    {* check if we need a http-equiv refresh *}
    {if $site.redirect}
    <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />

    {/if}
    {foreach $site.http_equiv as $key => $item}
        <meta name="{$key|wash}" content="{$item|wash}" />

    {/foreach}
    {foreach $site.meta as $key => $item}
    {if is_set( $module_result.content_info.persistent_variable[$key] )}
        <meta name="{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
    {else}
        <meta name="{$key|wash}" content="{$item|wash}" />
    {/if}

    {/foreach}

    {* Prefer chrome frame on IE 8 and lower, or at least as new engine as possible *}
    <!--[if lt IE 9 ]>
        <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1" />
    <![endif]-->

    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta name="generator" content="eZ Publish" />

{if $canonical_link}
    {include uri="design:canonical_link.tpl"}
{/if}

{if $enable_link}
    {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
{/if}

{/default}

{literal}
<link media="all" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>



<script type="text/javascript">

$(function() {  

      // To keep track of how many images have loaded
	  
	  /*var browsers = [ 'aol',       'camino',    'firefox',   'flock',
                 'icab',      'konqueror', 'mozilla',   'msie',
                 'netscape',  'opera',     'safari'                 ];*/

	  
	if ($.browser.msie || $.browser.safari || $.browser.mozilla ){
	
	
		$(function(){
	
				$("#navigation li").not(".current_page").hover(function(e){
					$(this).css({"background-image":"url(http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
					$(this).find("a").css({"background-image":"url(http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top", "color":"#23436a"});
				}, function(e){
					$(this).css({"background":"none"});
					$(this).find("a").css({"background":"none", "color":"white"});
				});
				$("#theImages a").click(function(){
					var clicked = this;
					$(".thumb_frame").hide();
					$(clicked).find(".thumb_frame").show();
				});
				var imgCaps = $(".imageCaption h3");
				$(".thumb_caption").each(function(i){
					this.innerHTML = imgCaps[i].innerHTML;
				});
				$("#accordion").accordion({
					fillSpace: true
				});
				$("#results_tabs").tabs();
				$("#leaderboards_tabs").tabs();
				$("#sales_tabs").tabs();
			});  
	
	}
	
	else{
	
	  
	  
    var loaded = 0;

      // Let's retrieve how many images there are
    var numImages = $("img").length;

      // Let's bind a function to the loading of EACH image
    $("img").load(function() {

          // One more image has loaded
        ++loaded;

          // Only if ALL the images have loaded
        if (loaded === numImages) {

		alert("in loaded");
              // This will be executed ONCE after all images are loaded.
            $(function(){
	
				$("#navigation li").not(".current_page").hover(function(e){
					$(this).css({"background-image":"url(http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/bg_left.png)", "background-repeat":"no-repeat", "background-position":"left top"});
					$(this).find("a").css({"background-image":"url(http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/bg_right.png)", "background-repeat":"no-repeat", "background-position":"right top", "color":"#23436a"});
				}, function(e){
					$(this).css({"background":"none"});
					$(this).find("a").css({"background":"none", "color":"white"});
				});
				$("#theImages a").click(function(){
					var clicked = this;
					$(".thumb_frame").hide();
					$(clicked).find(".thumb_frame").show();
				});
				var imgCaps = $(".imageCaption h3");
				$(".thumb_caption").each(function(i){
					this.innerHTML = imgCaps[i].innerHTML;
				});
				$("#accordion").accordion({
					fillSpace: true
				});
				$("#results_tabs").tabs();
				$("#leaderboards_tabs").tabs();
				$("#sales_tabs").tabs();
			});  
        }
    });
	
	}
});



</script>
{/literal}