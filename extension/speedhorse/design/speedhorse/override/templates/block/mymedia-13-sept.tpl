<div id="content_slide"><!--start div#content-->
			<div id="carousel_drop"></div>
<div id="multimedia_slideshow">

<div id="preview_pane">
{def $children3 = array()
   $children_count3 = ''}
 {def $children4 = array()
   $children_count4 = ''}  
{def $sel=1} 
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $valarticle=""}	                   
                 	
                    {set $children_count3=fetch_alias( 'children_count', hash( 'parent_node_id', '670') )}
       			   {if $children_count3}  
					{foreach fetch_alias( 'children', hash( 'parent_node_id', '670','sort_by', array( 'priority', true()) )  ) as $child3}
                            {if eq($sel,1)}
                           <div class="preview_content {attribute_view_gui attribute=$child3.data_map.short_name} current_view">
                               	{set $children_count4=fetch_alias( 'children_count', hash( 'parent_node_id', $child3.node_id) )}
                                     {if $children_count4}
                                            {foreach fetch_alias( 'children', hash( 'parent_node_id', $child3.node_id,'sort_by', array( 'priority', true())) ) as $child1 max 1}
                                            	{if eq($child1.class_identifier,'article')}
                                                    {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                    {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                    {set $rvalue=''}
                                                    {foreach $related_objects1 as $key1 => $value1}	
                                                        {if eq($value1.contentclass_id,'33')}
                                                        {set $rvalue=$value1.id}
                                                        {/if}
                                                    {/foreach}
                                                    {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                    {attribute_view_gui attribute=$valarticle.data_map.image id=$child3.data_map.short_name}
                                                    <div class="large_image_caption_wrap">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.title}</div>
                                                        <div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
						     
                                              {/if} <!-- article -->
                                			
                                              {if eq($child1.class_identifier,'image')}
	                                            
                                                {attribute_view_gui attribute=$child1.data_map.image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.caption}</h3>{attribute_view_gui attribute=$child1.data_map.caption}</div>
                                        			<div class="more_link_wrap"></div>
                                    			</div>
						                       {/if} <!-- image -->   		
                                              
                                              {if eq($child1.class_identifier,'botr_video')}
                                                 {attribute_view_gui attribute=$child1.data_map.video_image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.name}</h3>{attribute_view_gui attribute=$child1.data_map.description}</div>
                                        			<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                    			</div> 
                                              {/if}  <!-- botrvideo -->
                                              
                                              {if eq($child1.class_identifier,'blog_post')}
	                                            {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                   {attribute_view_gui attribute=$valarticle.data_map.image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.intro}</div>
                                        			<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                    			</div>   
                                                     
                                                {/if}      <!-- blog -->
                                                
                                            {/foreach}
                                      {/if} 
                             </div> 
                             {set sel=$sel|inc}
                              {else}
                               
                               <div class="preview_content {attribute_view_gui attribute=$child3.data_map.short_name}">
                               	{set $children_count4=fetch_alias( 'children_count', hash( 'parent_node_id', $child3.node_id) )}
                                     {if $children_count4}
                                            {foreach fetch_alias( 'children', hash( 'parent_node_id', $child3.node_id,'sort_by', array( 'priority', true()) )  ) as $child1 max 1}
                                            	{if eq($child1.class_identifier,'article')}
                                                    {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                    {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                    {set $rvalue=''}
                                                    {foreach $related_objects1 as $key1 => $value1}	
                                                        {if eq($value1.contentclass_id,'33')}
                                                        {set $rvalue=$value1.id}
                                                        {/if}
                                                    {/foreach}
                                                    {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                    {attribute_view_gui attribute=$valarticle.data_map.image id=$child3.data_map.short_name}
                                                    <div class="large_image_caption_wrap">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.title}</div>
                                                        <div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                    </div>
						     
                                              {/if} <!-- article -->
                                			
                                              {if eq($child1.class_identifier,'image')}
	                                            
                                                {attribute_view_gui attribute=$child1.data_map.image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.caption}</h3>{attribute_view_gui attribute=$child1.data_map.caption}</div>
                                        			<div class="more_link_wrap"></div>
                                    			</div>
						                       {/if} <!-- image -->   		
                                            
                                            	{if eq($child1.class_identifier,'botr_video')}
                                                 {attribute_view_gui attribute=$child1.data_map.video_image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.name}</h3>{attribute_view_gui attribute=$child1.data_map.description}</div>
                                        			<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                    			</div> 
                                              {/if}  <!-- botrvideo -->
                                              
                                               {if eq($child1.class_identifier,'blog_post')}
	                                            {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                   {attribute_view_gui attribute=$valarticle.data_map.image id=$child3.data_map.short_name}
			                                    <div class="large_image_caption_wrap">
                                        			<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.intro}</div>
                                        			<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                    			</div>   
                                                     
                                                {/if}  <!-- blog -->
                                                	
                                            {/foreach}
                                      {/if} 
                             </div> 
                              {/if} <!-- sel -->         
                       {/foreach}
                      {/if}       
                                
                  
                    
    
</div><!-- #preview_pane -->				
                
             

{def $children6 = array()
                 	$children_count6 = ''}
{def $sel=1}                    
                 	{set $children_count6=fetch_alias( 'children_count', hash( 'parent_node_id', '670') )}
       				 {if $children_count6}
                     <ul id="slideshow_menu" style="z-index:1000">
                   			{foreach fetch_alias( 'children', hash( 'parent_node_id', '670','sort_by', array( 'priority', true()) )  ) as $child6 }
                            			{if eq($sel,1)}
                                            <li><a id={attribute_view_gui attribute=$child6.data_map.short_name} class="current_view">{attribute_view_gui attribute=$child6.data_map.name}</a></li>
                                            {set sel=$sel|inc}
                                        {else}
                                        	<li><a id={attribute_view_gui attribute=$child6.data_map.short_name}>{attribute_view_gui attribute=$child6.data_map.name}</a></li>
                                        {/if}
                  			{/foreach}
                     </ul>                            
       				{/if}  
{undef $children6}		
{undef $children_count6}        
{undef $sel}
{undef $child6}
        		

				<div id="arrow_left"></div>
				<div id="arrow_right"></div>

{def $defplayer = ezini( 'BOTRSettings', 'DefaultPlayer', 'botr.ini' )}
    {if is_set($player)|not}
    {def $player = $defplayer}
    {/if}
    {if $player|eq('')}
        {set $player = $defplayer}
    {/if}
    {def $player_r = botr_player($player)}
    {def $vid_id = ""}
    <script type="text/javascript" src="/extension/multimedia/design/standard/javascript/ezbotr_object.js"></script>

{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}

{* FOR TOP STORIES *}
{def $children1 = array()
     $children_count1 = ''}

{* FOR PHOTOS *}
{def $children = array()
     $children_count = ''}

{* FOR VIDEOS *}
{def $children2 = array()
     $children_count2 = ''}                
		
        		<div id="gallery_container">
{def $children6 = array()
   $children_count6 = ''}
{def $sel=1}                    
                 	{set $children_count6=fetch_alias( 'children_count', hash( 'parent_node_id', '670') )}
       				 {if $children_count6}                
                        {foreach fetch_alias( 'children', hash( 'parent_node_id', '670','sort_by', array( 'priority', true()) )  ) as $child6}
                               {if eq($sel,1)}
								<div class="the_images {attribute_view_gui attribute=$child6.data_map.short_name} current_view">
                                    {set $children_count1=fetch_alias( 'children_count', hash( 'parent_node_id', $child6.node_id) )}
                                     {if $children_count1}
                                            {foreach fetch_alias( 'children', hash( 'parent_node_id', $child6.node_id,'sort_by', array( 'priority', true()) )  ) as $child1}
                                            	{if eq($child1.class_identifier,'article')}
	                                            {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                     <div class="thumb_link">
                                                       {attribute_view_gui attribute=$valarticle.data_map.image}
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        	<div class="image_caption">
                                                        		<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.title}</div>
                                                        		<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                        		</div>
                                                    </div>
                                                  {/if}
                                                  {if eq($child1.class_identifier,'image')}
                                    	  			<div class="thumb_link">
                                          			  {attribute_view_gui attribute=$child1.data_map.image data-fullsize=$child1.data_map.image}
                                             	      <div class="thumb_caption"></div>
                                                  	  <div class="thumb_frame" style="display:block;"></div>
                                                   	  <div class="image_caption">
                                                       	<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.caption}</h3></div>
                                                       	<div class="more_link_wrap"></div>
                                                   	  </div>
                                         		   </div>
                                                {/if}
                                                {if eq($child1.class_identifier,'botr_video')}
                                                  <div class="thumb_link basic" id="thumb_{$child.node_id}">
                                                    {attribute_view_gui attribute=$child1.data_map.video_image data-fullsize=$child1.data_map.video_image}
                                                     <div class="thumb_caption"></div>
                                                     <div class="thumb_frame" style="display:block;"></div>
                                                       <div class="image_caption">
                                                         <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.description}</h3></div>
                                                         <div class="more_link_wrap"></div>
                                                     </div>
                                                  </div>   
                                                {/if} 
                                                
                                                {if eq($child1.class_identifier,'blog_post')}
	                                            {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child1.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                                     <div class="thumb_link">
                                                       {attribute_view_gui attribute=$valarticle.data_map.image}
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        	<div class="image_caption">
                                                        		<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child1.data_map.title}</h3>{attribute_view_gui attribute=$child1.data_map.title}</div>
                                                        		<div class="more_link_wrap"><a href={$child1.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                        		</div>
                                                    </div>
                                                  {/if}      
                                            {/foreach}
                                    {/if} 	
                               </div><!-- .top_stories -->
                               {set sel=$sel|inc}
                          {else}
                          		<div class="the_images {attribute_view_gui attribute=$child6.data_map.short_name}">
				                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $child6.node_id) )}
       				 				{if $children_count}
                   					{foreach fetch_alias( 'children', hash( 'parent_node_id', $child6.node_id,'sort_by', array( 'priority', true()))  ) as $child}
                                    	{if eq($child.class_identifier,'image')}
                                    	  <div class="thumb_link">
                                          {attribute_view_gui attribute=$child.data_map.image data-fullsize=$child.data_map.image}
                                              <div class="thumb_caption"></div>
                                                   <div class="thumb_frame" style="display:block;"></div>
                                                   <div class="image_caption">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child.data_map.caption}</h3></div>
                                                        <div class="more_link_wrap"></div>
                                                   </div>
                                          </div>
                                        {/if}
                                        {if eq($child.class_identifier,'botr_video')}
                               	            <div class="thumb_link basic" id="thumb_{$child.node_id}">
                                           		{attribute_view_gui attribute=$child.data_map.video_image data-fullsize=$child.data_map.video_image}
                                                <div class="thumb_caption"></div>
                                                <div class="thumb_frame" style="display:block;"></div>
                                                <div class="image_caption">
                                                        <div class="large_image_caption"><h3>{attribute_view_gui attribute=$child.data_map.description}</h3></div>
                                                        <div class="more_link_wrap"></div>
                                                </div>
                                            </div>   
                                        {/if}
                                        {if eq($child.class_identifier,'article')}
                                          {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                    	   			<div class="thumb_link">
                                                       {attribute_view_gui attribute=$valarticle.data_map.image}
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        	<div class="image_caption">
                                                        		<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child.data_map.title}</h3>{attribute_view_gui attribute=$child.data_map.title}</div>
                                                        		<div class="more_link_wrap"><a href={$child.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                        	</div>
                                                   </div>    
                                        {/if}  
                                        
                                        {if eq($child.class_identifier,'blog_post')}
                                          {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child.node_id ) )}
                                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                {set $rvalue=''}
                                                {foreach $related_objects1 as $key1 => $value1}	
                                                    {if eq($value1.contentclass_id,'33')}
                                                    {set $rvalue=$value1.id}
                                                    {/if}
                                                {/foreach}
                                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                    	   			<div class="thumb_link">
                                                       {attribute_view_gui attribute=$valarticle.data_map.image}
                                                        <div class="thumb_caption"></div>
                                                        <div class="thumb_frame" style="display:block;"></div>
                                                        	<div class="image_caption">
                                                        		<div class="large_image_caption"><h3>{attribute_view_gui attribute=$child.data_map.title}</h3>{attribute_view_gui attribute=$child.data_map.title}</div>
                                                        		<div class="more_link_wrap"><a href={$child.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
                                                        	</div>
                                                   </div>    
                                        {/if}       
                   			  		{/foreach}
       								{/if}  
								</div><!-- .photos -->
                          
                          
                          
                          {/if}                  
						{/foreach} 
                      {/if}                 
                
                   
                   
                  	
                    
				</div><!--end div#preview_pane-->
			</div><!-- #multimedia_slideshow -->
		</div><!-- Content_slide -->	