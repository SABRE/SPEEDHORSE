<div style="width:940px"><div style="float:left;">
<div id="content"><!--start div#content-->
			
			<div id="the_post">
            
            	<h2><a href={$node.url_alias|ezurl()}>{$node.data_map.title.content|wash()}</a></h2>
                <!--<h2>TITLE GOES HERE AND CAN BE ON TWO LINES IF NEED BE</h2> In the case this does not need to be a link comment out the above and uncomment this-->
                <div class="post-info">Published {$node.object.published|l10n(shortdatetime)} &nbsp;&nbsp;|&nbsp;&nbsp; Associated Press</div>
            	
            	<div class="info_bar">
                    <div class="left-area">
                        <a href="#" class="print">Print</a>
                        <a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}" class="email">Email</a>
                    </div><!--end div.left-area-->
                    <div class="social">
                        <img src="../images/fb_like.jpg" />
                        <img src="../images/fb_people_like.jpg" />
                        <img src="../images/twitter.jpg" />
                    </div><!--end div.social-->
                </div><!--end div.info_bar-->
                
                <div id="the_post_content">
                	 <div class="float_img_left">{attribute_view_gui attribute=$node.data_map.image image_class=article_inner_img}</div>
                	<div style="text-align:justify">{attribute_view_gui attribute=$node.data_map.body}</div>
                </div><!--end div#the_post_content-->
                
                <div class="info_bar">
            	<div class="left-area">
                	<a href="#" class="print">Print</a>
                    <a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}" class="email">Email</a>
                </div><!--end div.left-area-->
                <div class="social">
                	<img src="../images/fb_like.jpg" />
                    <img src="../images/fb_people_like.jpg" />
                    <img src="../images/twitter.jpg" />
                </div><!--end div.social-->
            </div><!--end div.info_bar-->
            </div><!--end div#the_post-->

			<div id="bottom_content_wrap">
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
            
            <div id="comments">
            	<h2>Comments (3)</h2>
                <form method="post" action="">
                	<img src="../images/post_comment_avatar.jpg" alt="Avatar" id="form_avatar" />
                    <div id="form_right">
                    	<textarea name="">Add a comment...</textarea>
                        <div id="form_right_bottom">
                        	<label for="facebook_post"><input type="checkbox" id="facebook_post" name="facebook_post" /> Post to Facebook</label>
                            <span class="posting_as">Posting as Justing Kellner <a href="#">(Change)</a></span>
                            <input type="submit" id="submit_comment" value="Comment" />
                        </div><!--end div#form_right_bottom-->
                    </div><!--end div#form_right-->
                </form><!--end form-->
                <div class="comment">
                	<img src="../images/post_comment_avatar.jpg" alt="Avatar" class="comment_author_av" />
                    <div class="comment_right">
                    	<h4>Justin Kellner</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in arcu eu nunc placerat vestibulum
vitae at metus. Proin sed lacus justo. Nulla ut fermentum odio.</p>
                        <span>
                        	<a href="#">Like</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Reply</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Subscribe</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <cite>14 hours ago</cite>
                        </span><!--end span-->
                    </div><!--end div.comment_right-->
                </div><!--end div.comment-->
                <div class="comment">
                	<img src="../images/post_comment_avatar.jpg" alt="Avatar" class="comment_author_av" />
                    <div class="comment_right">
                    	<h4>Justin Kellner</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in arcu eu nunc placerat vestibulum
vitae at metus. Proin sed lacus justo. Nulla ut fermentum odio.</p>
                        <span>
                        	<a href="#">Like</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Reply</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Subscribe</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <cite>14 hours ago</cite>
                        </span><!--end span-->
                    </div><!--end div.comment_right-->
                </div><!--end div.comment-->
                <div class="comment">
                	<img src="../images/post_comment_avatar.jpg" alt="Avatar" class="comment_author_av" />
                    <div class="comment_right">
                    	<h4>Justin Kellner</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in arcu eu nunc placerat vestibulum
vitae at metus. Proin sed lacus justo. Nulla ut fermentum odio.</p>
                        <span>
                        	<a href="#">Like</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Reply</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <a href="#">Subscribe</a>
                            &nbsp;&nbsp;&middot;&nbsp;&nbsp;
                            <cite>14 hours ago</cite>
                        </span><!--end span-->
                    </div><!--end div.comment_right-->
                </div><!--end div.comment-->
            </div><!--end div#comments-->
		</div><!--end div#content-->

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