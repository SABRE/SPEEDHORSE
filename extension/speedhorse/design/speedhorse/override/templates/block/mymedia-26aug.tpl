<div id="content_slide"><!--start div#content-->
			<div id="carousel_drop"></div>
<div id="multimedia_slideshow">
				<div id="preview_pane">
					<div class="preview_content top_stories current_view">
						<img src="../images/image1_big.jpg" id="story_preview" alt="big_image">
						<div class="large_image_caption_wrap">
							<div class="large_image_caption"><h3>story title 1 goes here</h3>Lorem ipsum dolor sit amet, cons ec tetur adipiscing elit est commodo malesuada, felis tellus sagittis orci, sit amet semper mi ipsum dolor faucibus.</div>
							<div class="more_link_wrap"><a href="#" class="more_link">read more &#xBB;</a></div>
						</div>
					</div>
					<div class="preview_content photos">
						<img src="../images/image2_big.jpg" id="photos_preview" alt="big_image">
						<div class="large_image_caption_wrap">
							<div class="large_image_caption"><h3>photo set title here</h3>Lorem ipsum dolor sit amet, cons ec tetur adipiscing elit est commodo malesuada, felis tellus sagittis orci, sit amet semper mi ipsum dolor faucibus.</div>
							<div class="more_link_wrap"><a href="#" class="more_link">read more &#xBB;</a></div>
						</div>
					</div>
					<div class="preview_content video">
						<img src="../images/image3_big.jpg" id="video_preview" alt="big_image">
						<div class="large_image_caption_wrap">
							<div class="large_image_caption"><h3>video title here</h3>Lorem ipsum dolor sit amet, cons ec tetur adipiscing elit est commodo malesuada, felis tellus sagittis orci, sit amet semper mi ipsum dolor faucibus.</div>
							<div class="more_link_wrap"><a href="#" class="more_link">read more &#xBB;</a></div>
						</div>
					</div>
				</div><!-- #preview_pane -->

				<ul id="slideshow_menu" style="z-index:1000">
					<li><a id="top_stories" class="current_view">top stories</a></li>
					<li><a id="photos">photos</a></li>
					<li><a id="video">video</a></li>
				</ul>

				<div id="arrow_left"></div>
				<div id="arrow_right"></div>

				<div id="gallery_container">
					<div class="the_images top_stories current_view">
					
                    {def $children1 = array()
                 	$children_count1 = ''}
                 	{set $children_count1=fetch_alias( 'children_count', hash( 'parent_node_id', '244') )}
       				 {if $children_count1}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '244')  ) as $child1}
                               	                 <div class="thumb_link">
                                                    {attribute_view_gui attribute=$child1.data_map.image}
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        <div class="image_caption">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.title}</div>
                                                        <div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
                                                </div>     
                   			{/foreach}
       				{/if} 	

					</div><!-- .top_stories -->



					<div class="the_images photos">
                    
                    {def $children = array()
                 	$children_count = ''}
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '220') )}
       				 {if $children_count}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '220')  ) as $child}
                               	                 <div class="thumb_link">
                                           {attribute_view_gui attribute=$child.data_map.image data-fullsize=$child.data_map.image}
                                                    
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        <div class="image_caption">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child.data_map.caption}</h3>{attribute_view_gui attribute=$child.data_map.caption}</div>
                                                        <div class="more_link_wrap"><a href={$child.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
                                                </div>     
                   			{/foreach}
       				{/if}  

                    
					</div><!-- .photos -->

					<div class="the_images video">
					{def $children2 = array()
                 	$children_count2 = ''}
                 	{set $children_count2=fetch_alias( 'children_count', hash( 'parent_node_id', '541') )}
       				 {if $children_count2}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '541')  ) as $child2}
                               	<div class="thumb_link">
                                {attribute_view_gui attribute=$child2.data_map.thumbnail data-fullsize=$child2.data_map.thumbnail}
                                <div class="thumb_caption"></div>
                                <div class="thumb_frame" style="display:block;"></div>
                                <div class="image_caption">
                                <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child2.data_map.description}</h3>{attribute_view_gui attribute=$child2.data_map.description}</div>
                                <div class="more_link_wrap"><a href={$child2.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                </div>
                                </div>     
                   			{/foreach}
       				{/if} 	

                        	
                    </div><!-- .video -->

				</div><!--end div#preview_pane-->
			</div><!-- #multimedia_slideshow -->
		</div><!-- Content_slide -->	