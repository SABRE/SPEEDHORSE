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
					<div><a id="features_ad_link" href={attribute_view_gui attribute=$valarticle.data_map.company_website} target="_blank">{attribute_view_gui attribute=$valarticle.data_map.sponsores_image }</a></div>
 
{undef $my_node}
{undef $myobjid}
{undef $related_objects}
{undef $valarticle}
				
				<div id="features_content">
					<table id="features_table">
						<colgroup>
							<col style="width:324px;">
							<col style="width:285px;">
						</colgroup>
  					<tr>
                

					{foreach $valid_nodes as $key => $feature_stories}
                    	{def $vid_id = ""}
                        {def $my_node1=fetch( 'content', 'node', hash( 'node_id', $feature_stories.node_id ) )}
        
        				
        
                        {let related_objects1=fetch( 'content', 'related_objects',
                            hash( 'object_id', $my_node1.object.id  ) )}
                                        
                        {def $rvalue=''}
                        {foreach $related_objects1 as $key => $value1}	
                            {set $rvalue=$value1.id}
                        {/foreach}
                         
                        {def $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))}
                        
                
						{if gt($col,2)}
							
							{set $col=1}
							{set col=$col|inc}
							</tr><tr>
							
							{else}
								
							{set col=$col|inc}
							{/if}
								
								<td>
								<div class="post">
									<div style="float: left;margin-right: 10px;">{attribute_view_gui attribute=$valarticle.data_map.image image_class=artical_img  href=$feature_stories.url_alias|ezurl()}</div>
									
									<h3><a href={$feature_stories.url_alias|ezurl()}>{attribute_view_gui attribute=$feature_stories.data_map.headline}</a></h3>
									<h5><a href="#">-{$feature_stories.object.current.creator.name|wash()}</a></h5>
																
									
									{attribute_view_gui attribute=$feature_stories.data_map.intro}
									
									
									
								</div>
							</td>
							{/foreach}
						</tr>
					</table><!-- #features_table -->
				</div><!-- #features_content -->
			</div><!-- #features -->
		</div><!-- content_features -->	
			
