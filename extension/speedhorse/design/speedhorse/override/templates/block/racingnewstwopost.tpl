{def $col=1}
{def $valid_nodes = $block.valid_nodes}
<div id="content_splshopheadline">
<div id="two_posts_wrap">
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}

	{foreach $valid_nodes as $key => $race_twoimg}
 		{set $my_node1=fetch( 'content', 'node', hash( 'node_id', $race_twoimg.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                 
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}   
    
		{if gt($col,1)}
				{set $col=1}
					 <div class="post right_post">
					 	<div style="z-index:3">
							 {attribute_view_gui attribute=$valarticle.data_map.image css_class=post right_post image_class=racingnewstwopost_img}
						</div>	 
						<a href={$race_twoimg.url_alias|ezurl()}>
						<div style="margin-top:-207px;z-index:2;position: absolute;">
							<h3>{attribute_view_gui attribute=$race_twoimg.data_map.short_title|title}</h3>
						</div>	
						</a>
					</div>			
				{else}
					<div class="post" >
						<div style="z-index:3">
							{attribute_view_gui attribute=$valarticle.data_map.image css_class=post image_class=racingnewstwopost_img}
						</div>
						<a href={$race_twoimg.url_alias|ezurl()}>
						<div style="margin-top:-207px;z-index:4;position: absolute;">
							<h3>{attribute_view_gui attribute=$race_twoimg.data_map.title}</h3>
						</div>
						</a>
					</div>
				{set col=$col|inc}
				{/if}
		
	{/foreach}		
            	
               
         </div>
</div>			