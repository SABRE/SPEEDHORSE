{def $col=1}
{def $valid_nodes = $block.valid_nodes}
{* $block.name *}
<div id="content_features" style="z-index:500">
<div id="features">
				<div style="vertical-align:middle"><h2 class="section_title">{$block.name}</h2></div>
                {def $pid=0}
                {foreach $valid_nodes as $key => $classifycat max 1}
                {set $pid=$classifycat.parent_node_id}
                {/foreach}
                
				{def $my_node=fetch( 'content', 'node', hash( 'node_id', $pid ) )}
                {let related_objects=fetch( 'content', 'related_objects',
					hash( 'object_id', $my_node.object.id  ) )}
 				{def $myobjid=0}
				{section loop=$related_objects var=related_object}
					{set $myobjid=$related_object.id}
 				{/section}	

				 {def $valarticle=fetch( 'content', 'object', hash( 'object_id', $myobjid ))}
					<div style="float:right;"><a id="features_ad_link" href={attribute_view_gui attribute=$valarticle.data_map.company_website} target="_blank"><img src="/extension/speedhorse/design/speedhorse/images/sponsor_article.png" />{attribute_view_gui attribute=$valarticle.data_map.sponsores_image image_class=articalsponsor_img}</a></div>
 
{undef $my_node}
{undef $myobjid}
{undef $related_objects}
{undef $valarticle}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
{def $valarticle=""}

				
				<div id="features_content" style="width:600px;">
					
					{foreach $valid_nodes as $key => $feature_stories}
                    
                    	{set $my_node1=fetch( 'content', 'node', hash( 'node_id', $feature_stories.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                                
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                    {/if}
                                {/foreach}
                                 
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}

                    
						{set $chk=$col|mod(2)}
                        
						{if $chk|eq(0)}
						<div style="float:left;" class="post">
                        	
                                                        				
                                        <div style="float: left;margin-right: 10px;">{attribute_view_gui attribute=$valarticle.data_map.image  image_class=artical_img  href=$feature_stories.url_alias|ezurl()}</div>
                                        <h3><a href={$feature_stories.url_alias|ezurl()}>{attribute_view_gui attribute=$feature_stories.data_map.headline}</a></h3>
                                        <h5><a href="#">-{$feature_stories.object.current.creator.name|wash()}</a></h5>
                                        {attribute_view_gui attribute=$feature_stories.data_map.intro}
                        </div>
                        {else}
                        <div style="float:right;" class="post">
                               
                               
                                        <div style="float: left;margin-right: 10px;">{attribute_view_gui attribute=$valarticle.data_map.image  image_class=artical_img  href=$feature_stories.url_alias|ezurl()}</div>
                                        <h3><a href={$feature_stories.url_alias|ezurl()}>{attribute_view_gui attribute=$feature_stories.data_map.headline}</a></h3>
                                        <h5><a href="#">-{$feature_stories.object.current.creator.name|wash()}</a></h5>
                                        {attribute_view_gui attribute=$feature_stories.data_map.intro}
                        </div>
                        {/if}
			 			{set col=$col|inc}
					{/foreach}
                  
                       
                       
		</div><!-- #features_content -->
	</div><!-- #features -->
</div><!-- content_features -->	
			
