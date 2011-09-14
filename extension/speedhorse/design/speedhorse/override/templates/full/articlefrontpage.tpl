<div style="width:940px"><div style="float:left;">
<div id="content"  style="z-index:9999;"><!--start div#content-->
			
			<div id="the_post">
            
            	<h2><a href={$node.url_alias|ezurl()}>{$node.data_map.title.content|wash()}</a></h2>
                <!--<h2>TITLE GOES HERE AND CAN BE ON TWO LINES IF NEED BE</h2> In the case this does not need to be a link comment out the above and uncomment this-->
                <div class="post-info"><h3><span style="font-weight:bold;">{$node.data_map.publish_date.data_int|datetime( 'mydate', '%F')} {$node.data_map.publish_date.data_int|datetime( 'mydate1', '%d' )}, {$node.data_map.publish_date.data_int|datetime( 'mydate4', '%Y' )}</span>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/userdetail/list/{$node.object.current.creator.id}">{$node.object.current.creator.name|wash()}</a></h3></div>
            	
            	<div class="info_bar">
                    <div class="left-area">
                        <a href="#" class="print">Print</a>
                        <a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}" class="email">Email</a>
                    </div><!--end div.left-area-->
                    <div class="social">
                        <iframe style="width:100px;" src="//www.facebook.com/plugins/like.php?app_id=216190148439088&amp;href=http%3A%2F%2Fcollegeyardart.com{$node.url_alias|ezurl(no)}&amp;send=false&amp;layout=button_count&amp;width=255&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:225px; height:21px;" allowTransparency="true"></iframe>
                    </div><!--end div.social-->
                </div><!--end div.info_bar-->
                
                
              {def $defplayer = ezini( 'BOTRSettings', 'DefaultPlayer', 'botr.ini' )}
                {if is_set($player)|not}
                    {def $player = $defplayer}
                {/if}
                
                {if $player|eq('')}
                    {set $player = $defplayer}
                {/if}
                {def $player_r = botr_player($player)}
                {def $vid_id = ""}
				{def $my_node=fetch( 'content', 'node', hash( 'node_id', $node.node_id ) )}

				{let related_objects=fetch( 'content', 'related_objects',
					hash( 'object_id', $my_node.object.id  ) )}
                 				
                {def $rvalue=''}
                {foreach $related_objects as $key => $value1}	
                	{set $rvalue=$value1.id}
                {/foreach}
             
                <div id="the_post_content" style="z-index:5;" >
                	  {def $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                     	{set $vid_id = $valarticle.data_map.botr_video.content}
                   	  {if $vid_id}
                      	                    
                      <div class="float_img_left">
                         <div id="botr_{$vid_id}_{$player}_div" style="width: 307px; height: 255px; margin: 5px;" class="botrplayer">
                        
                        <embed type="application/x-shockwave-flash" src="http://d2ciznq2rtdp7k.cloudfront.net/player.12615.swf" id="botr_{$vid_id}_{$player}_swf" name="botr_{$vid_id}_{$player}_swf" allowfullscreen="true" allowscriptaccess="always" bgcolor="#000000" wmode="opaque" flashvars="id=botr_{$vid_id}_{$player}_swf&amp;playlist=none&amp;repeat=list&amp;file=http%3A%2F%2Fcdn.thinkcreative.com%2Fjwp%2F{$vid_id}.xml&amp;title=video1&amp;ping.script=http%3A%2F%2Fcdn.thinkcreative.com%2Fpings%2F&amp;image=http%3A%2F%2Fcdn.thinkcreative.com%2Fthumbs%2F{$vid_id}-480.jpg&amp;stretching=uniform&amp;height=270&amp;width=480&amp;controlbar=over&amp;autostart=false&amp;playlistsize=200&amp;playerready=jwplayer.api.playerReady" height="240" width="290"></div>
 </div>
{undef $my_node}
{undef $myobjid}
{undef $related_objects}
{undef $valarticle}

					{else}
                       {def $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                 	<div class="float_img_left">{attribute_view_gui attribute=$valarticle.data_map.image image_class=article_inner_img}</div>
                     {/if}
					 
					
					
					<div style="text-align:justify">{attribute_view_gui attribute=$node.data_map.body}</div>
			
				
                </div><!--end div#the_post_content-->
                
                <div class="info_bar">
            	<div class="left-area">
                	<a href="#" class="print">Print</a>
                    <a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}" class="email">Email</a>
                </div><!--end div.left-area-->
                <div class="social">
                	<iframe style="width:100px;" src="//www.facebook.com/plugins/like.php?app_id=216190148439088&amp;href=http%3A%2F%2Fcollegeyardart.com{$node.url_alias|ezurl(no)}&amp;send=false&amp;layout=button_count&amp;width=255&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=verdana&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:225px; height:21px;" allowTransparency="true"></iframe>
                </div><!--end div.social-->
            </div><!--end div.info_bar-->
            </div><!--end div#the_post-->

			<div id="bottom_content_wrap" style="z-index:5;">
				<div id="left_bottom_panel">
					<h2 class="section_title">We Recommend</h2>
                    
                    <ul class="speedhorse_list">
					{def $children = array()
                 	$children_count = ''}
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '508') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '508')  ) as $child max 7}
                           				<li><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.title}</a></li>
                    			{/foreach}
       				{/if} 
            		</ul>
				</div><!-- #left_bottom_panel -->

				<div id="left_bottom_panel" class="right_bottom_panel"><!--start div#left_bottom_panel on the right-->
					<h2 class="section_title">Related News</h2>
					<ul class="speedhorse_list">
					{def $children = array()
                 	$children_count = ''}
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '517') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '517')  ) as $child max 7}
                           				<li><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.title}</a></li>
                    			{/foreach}
       				{/if} 
            		</ul>
				</div><!--end div#left_bottom_panel on the right -->
			</div><!--end div#bottom_content_wrap -->
           </div><!--end div#content--> 
            <div id="comments" style="z-index:1; margin-top:auto; position:relative;">
            {def $facebookurl=""}
			{set $facebookurl=concat('http://collegeyardart.com',$node.url_alias|ezurl(no))}
			<h2>Comments (<fb:comments-count href="{$facebookurl}"/></fb:comments-count>) </h2>
			{literal}
			 <script>(function(d){
              var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
              js = d.createElement('script'); js.id = id; js.async = true;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
              d.getElementsByTagName('head')[0].appendChild(js);
            }(document));</script>{/literal}
            <div class="fb-comments" data-href="{$facebookurl}" data-num-posts="2" data-width="500"></div>
            </div><!--end div#comments-->
		

</div><div style="float:right">
        <div id="sidebar"><!--start div#sidebar-->
			{def $children = array()
                 $children_count = ''}
                 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '266') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '266')  ) as $child max 1}
                           				{attribute_view_gui attribute=$child.data_map.ad_image href=$child.url_alias|ezurl()}
                    			{/foreach}
       				{/if}        
		<div style="height:35px;"></div>
	
			<a id="sidebar_site_link_1" href="#"></a>
			<a id="sidebar_site_link_2" href="#"></a>

			<h2 class="section_title">special focus title</h2>
            <ul class="speedhorse_list">
			{def $children = array()
                 $children_count = ''}
                 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '288') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '288')  ) as $child max 7}
                           				<li><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.title}</a></li>
                    			{/foreach}
       				{/if} 
            </ul>
            
            

		</div><!--end div#sidebar -->
        
</div></div>        