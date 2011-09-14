{def $valid_nodes = $block.valid_nodes}
<div id="sidebar"><!--start div#sidebar-->
	{foreach $valid_nodes as $key => $calendarvideoevent}	
    	<div id="video_week" style="float:left;">
            	<h2>{$block.name}</h2>
              
                    <div class="content-media" style="z-index:200;">
                {def $siteurl=concat( "http://", ezini( 'SiteSettings', 'SiteURL' ) )
                     $attribute_file=$calendarvideoevent.data_map.video
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
						insertMedia( '<param name="WMODE" value="transparent" />');
                        insertMedia( '<p>No <a href="http://www.macromedia.com/go/getflashplayer">Flash player<\/a> avaliable!<\/p>');
                        insertMedia( '<\/object>' );
                    //-->
                    </script>
                
                    <noscript>
                    <object type="application/x-shockwave-flash" data="{'flash/flash_player.swf'|ezdesign(no)}" width="448" height="354">
                        <param name="movie" value="{'flash/flash_player.swf'|ezdesign(no)}" />
                        <param name="scale" value="exactfit" />
						<PARAM NAME="WMODE" VALUE="transparent"> 
                        <param name="allowScriptAccess" value="sameDomain" />
                        <param name="allowFullScreen" value="true" />
                        <param name="flashvars" value="{$flash_var}" />
                        <p>No <a href="http://www.macromedia.com/go/getflashplayer">Flash player</a> avaliable!</p>
                    </object>
                    </noscript>
                    </div>

                <h3>{attribute_view_gui attribute=$calendarvideoevent.data_map.title}</h3>
                <span>{attribute_view_gui attribute=$calendarvideoevent.data_map.from_time} &nbsp;&nbsp;&nbsp; {attribute_view_gui attribute=$calendarvideoevent.data_map.city}, {attribute_view_gui attribute=$calendarvideoevent.data_map.state}</span>
                <p>{attribute_view_gui attribute=$calendarvideoevent.data_map.text}</p>
                <span class="readmore"><a href={$calendarvideoevent.url_alias|ezurl()} class="readmore">Read More &raquo;</a></span>
            </div><!--end div#video_week-->
      {/foreach}      
</div><!--end div#sidebar -->