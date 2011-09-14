{set-block scope=root variable=cache_ttl}600{/set-block}
{* Event - Full view *}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
    <div class="class-event">
    
    <div class="attribute-header">
    {if $node.data_map.title.has_content}
        <h1>{$node.data_map.title.content|wash()}</h1>
    {else}
        <h1>{$node.name|wash()}</h1>
    {/if}
    </div>
    
    <div class="attribute-byline">
    <p>
    {if $node.object.data_map.category.has_content}
    <span class="ezagenda_keyword">
    {"Category"|i18n("design/ezwebin/full/event")}:
    {attribute_view_gui attribute=$node.object.data_map.category}
    </span>
    {/if}
    
    <span class="ezagenda_date">{$node.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
    {if $node.object.data_map.to_time.has_content}
          - {$node.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
    {/if}
    </span>
    </p>
    </div>

    {* if $node.object.data_map.image.content}
         <div class="attribute-image">
             {attribute_view_gui attribute=$node.object.data_map.image align=center image_class=imagelarge}
        </div>
    {/if *}

    {if $node.object.data_map.text.has_content}
        <div class="attribute-short">{attribute_view_gui attribute=$node.object.data_map.text}</div>
    {/if}
    
    
<!--ashish-->
	<div class="content-media">
{def $siteurl=concat( "http://", ezini( 'SiteSettings', 'SiteURL' ) )
     $attribute_file=$node.data_map.video
     $video=concat( "content/download/",$attribute_file.contentobject_id,"/", $attribute_file.content.contentobject_attribute_id )|ezurl(no)
     $flash_var=concat( "moviepath=", $video )}

    {* Embed URL, which URL to retrieve the embed code from. *}
    {set $flash_var=$flash_var|append( "&amp;embedurl=", concat( $siteurl, "/flash/embed/", $node.object.id ) )}

    {* Embed Link *}
    {set $flash_var=$flash_var|append( "&amp;embedlink=", concat( $siteurl, $node.url_alias|ezurl(no) ) )}

    <script type="text/javascript">
    <!--
        insertMedia( '<object type="application/x-shockwave-flash"  data="{'flash/flash_player.swf'|ezdesign(no)}"  width="300" height="144"> ');
        insertMedia( '<param name="movie" value="{'flash/flash_player.swf'|ezdesign(no)}"  /> ');
        insertMedia( '<param name="scale" value="exactfit" /> ');
        insertMedia( '<param name="allowScriptAccess" value="sameDomain" />');
        insertMedia( '<param name="allowFullScreen" value="true" />');
        insertMedia( '<param name="flashvars" value="{$flash_var}" />');
        insertMedia( '<p>No <a href="http://www.macromedia.com/go/getflashplayer">Flash player<\/a> avaliable!<\/p>');
        insertMedia( '<\/object>' );
    //-->
    </script>

    <noscript>
    <object type="application/x-shockwave-flash" data="{'flash/flash_player.swf'|ezdesign(no)}" width="448" height="354">
        <param name="movie" value="{'flash/flash_player.swf'|ezdesign(no)}" />
        <param name="scale" value="exactfit" />
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="allowFullScreen" value="true" />
        <param name="flashvars" value="{$flash_var}" />
        <p>No <a href="http://www.macromedia.com/go/getflashplayer">Flash player</a> avaliable!</p>
    </object>
    </noscript>
    </div>
<!--ashish-->


    {* if $node.object.data_map.url.has_content}
        <p style="text-align:center;">
        <a href={$node.object.data_map.url.content|ezurl}>{$node.object.data_map.url.data_text|wash()}</a>
        </p>
    {/if *}
  </div>
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>