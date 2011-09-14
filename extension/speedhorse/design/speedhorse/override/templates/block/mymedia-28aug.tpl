<div id="content_slide"><!--start div#content-->
			<div id="carousel_drop"></div>
<div id="multimedia_slideshow">
				<div id="preview_pane">
					
                    {def $children3 = array()
                 	$children_count3 = ''}
                 	{set $children_count3=fetch_alias( 'children_count', hash( 'parent_node_id', '244') )}
       				 {if $children_count3}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '244')  ) as $child3 max 1}
                               	         <div class="preview_content top_stories current_view">
                                               {attribute_view_gui attribute=$child3.data_map.image id=story_preview}
                                               <div class="large_image_caption_wrap">
							<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child3.data_map.title}</h3>{attribute_view_gui attribute=$child3.data_map.title}</div>
                                              <div class="more_link_wrap"><a href={$child3.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                            </div>
                                        </div> 
                   			{/foreach}
       				{/if} 	
                   
                   
                    <!-- photo -->
                    {def $children4 = array()
                 	$children_count4 = ''}
                 	{set $children_count4=fetch_alias( 'children_count', hash( 'parent_node_id', '220') )}
       				 {if $children_count4}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '220')  ) as $child4 max 1}
                               	               <div class="preview_content photos">
                                           {attribute_view_gui attribute=$child4.data_map.image data-fullsize=$child4.data_map.image id=photos_preview}
                                                    <div class="large_image_caption_wrap">
															<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child4.data_map.caption}</h3>{attribute_view_gui attribute=$child4.data_map.caption}</div>
                                                        <div class="more_link_wrap"><a href={$child4.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
                                                </div>     
                   			{/foreach}
       				{/if}  
                    
					
                    
                   <!-- video -->
                   
                   {def $children5 = array()
                 	$children_count5 = ''}
                 	{set $children_count5=fetch_alias( 'children_count', hash( 'parent_node_id', '541') )}
       				 {if $children_count5}
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '541')  ) as $child5 max 1}
                               	          <div class="preview_content video">
                                           {attribute_view_gui attribute=$child5.data_map.video_image data-fullsize=$child5.data_map.video_image id=video_preview}
                                                  <div class="large_image_caption_wrap">
														<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child5.data_map.description}</h3>{attribute_view_gui attribute=$child5.data_map.description}</div>
                                                        <div class="more_link_wrap"><a href={$child5.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
                                             </div>     
                   			{/foreach}
       				{/if}  
                    
					                
                    
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
                                           {attribute_view_gui attribute=$child2.data_map.video_image data-fullsize=$child2.data_map.video_image}
                                                    
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