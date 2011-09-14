{def $valid_nodes = $block.valid_nodes}
<div id="bottom_content_wrap">
<div id="left_bottom_panel" class="right_bottom_panel"><!--start div#left_bottom_panel on the right-->
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}
					{foreach $valid_nodes as $key => $featureheadline}
					{set $my_node1=fetch( 'content', 'node', hash( 'node_id', $featureheadline.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                 
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))} 
                    <div class="post">
						<h4>{attribute_view_gui attribute=$featureheadline.data_map.short_title}</h4>
						<h3><a href={$featureheadline.url_alias|ezurl()}>{attribute_view_gui attribute=$featureheadline.data_map.headline image_class=blog_img}</a></h3>
						<div class="post_excerpt">
							{attribute_view_gui attribute=$valarticle.data_map.image css_class='post_content_image' image_class=artical_img href=$featureheadline.url_alias|ezurl()}
							
							<p align="justify">{attribute_view_gui attribute=$featureheadline.data_map.intro}</p>
							<div class="more_link_wrap"><a href={$featureheadline.url_alias|ezurl()} class="more_link">Read more &#xBB;</a></div>
						</div>
					</div>
				{/foreach}	
				</div><!--end div#left_bottom_panel on the right -->
			</div><!--end div#bottom_content_wrap -->