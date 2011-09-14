{def $valid_nodes = $block.valid_nodes}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}
				<div id="left_bottom_panel">
				{foreach $valid_nodes as $key => $singleheadline}	
                	{set $my_node1=fetch( 'content', 'node', hash( 'node_id', $singleheadline.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))} 
					<div class="post">
						<h4>{attribute_view_gui attribute=$singleheadline.data_map.title}</h4>
						<h3><a href={$singleheadline.url_alias|ezurl()}>{attribute_view_gui attribute=$singleheadline.data_map.headline}</a></h3>
						<div class="post_excerpt">
							{attribute_view_gui attribute=$valarticle.data_map.image css_class=post_content_image image_class=blog_img}
							{attribute_view_gui attribute=$singleheadline.data_map.intro}
							<div class="more_link_wrap"><a href={$singleheadline.url_alias|ezurl()} class="more_link">more &#xBB;</a></div>
						</div>
					</div>
                  {/foreach}        
				</div><!-- #left_bottom_panel -->

				
			