{def $valid_nodes = $block.valid_nodes}
<div id="featured_post_wrap" style="margin-bottom:20px;">
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $valarticle=""}

			{foreach $valid_nodes as $key => $bloglanding}
           
            {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $bloglanding.node_id ) )}
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                                
			  {attribute_view_gui attribute=$valarticle.data_map.image image_class=racingnewspecial_img href=$bloglanding.url_alias|ezurl()}
                <div class="post_content">
                    <h2>{attribute_view_gui attribute=$bloglanding.data_map.title}</h2>
                    <p>-{$bloglanding.object.current.creator.name|wash()}</p>
                    <p align="justify">{attribute_view_gui attribute=$bloglanding.data_map.intro}</p>
                    <a href={$bloglanding.url_alias|ezurl()} class="read-more">Read More &raquo;</a>
				{/foreach}	
                </div><!--end div.post_content-->
            </div><!--end div#featured_post_wrap-->
            
