{def $col=1}
{def $valid_nodes = $block.valid_nodes}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}
<div id="content_splshopheadline">
	{foreach $valid_nodes as $key => $race_specialfocustitle max 1}
   		 {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $race_specialfocustitle.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                 
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))} 
	<div id="featured_post_wrap">
               {attribute_view_gui attribute=$valarticle.data_map.image image_class=racingnewspecial_img href=$race_specialfocustitle.url_alias|ezurl()}
                <div class="post_content">
                    <h2>{attribute_view_gui attribute=$race_specialfocustitle.data_map.short_title}</h2>
                    <p>-{$race_specialfocustitle.object.current.creator.name|wash()}</p>
                    <p>{attribute_view_gui attribute=$race_specialfocustitle.data_map.intro}</p>
                    <a href={$race_specialfocustitle.url_alias|ezurl()} class="read-more">Read More &raquo;</a>
                </div><!--end div.post_content-->
            </div><!--end div#featured_post_wrap-->
	{/foreach}			
</div>