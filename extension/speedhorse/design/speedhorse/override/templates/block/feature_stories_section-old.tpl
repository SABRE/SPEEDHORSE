{def $col=1}
{def $valid_nodes = $block.valid_nodes}
<div id="content_features">
<div id="features">
				<h2 class="section_title">featured stories</h2>
				
				{def $my_node=fetch( 'content', 'node', hash( 'node_id', 244 ) )}
				fdvdv{$my_node.node_id}fvff
					ashish{attribute_view_gui attribute=$my_node.data_map.sponsored_link}kumar
					
					{$my_node.data_map.sponsored_link.object.id}
					
					{def $relnode}
						{foreach $my_node.data_map.sponsored_link.relation_list as $rel}
							{set $relnode = fetch(content, node, hash(node_id, $rel.node_id))}
							ooo{$relnode}ooo
							xx{attribute_view_gui attribute=$relnode.data_map.company_name}xx
							yy{attribute_view_gui attribute=$relnode.data_map.company_website}yy
							zz{attribute_view_gui attribute=$relnode.data_map.sponsores_image}zz
							
						
						 
					{/foreach}
					{undef $relnode}
				
				<a id="features_ad_link" href="#"></a>
				<div id="features_content">
					<table id="features_table">
						<colgroup>
							<col style="width:324px;">
							<col style="width:285px;">
						</colgroup>
  					<tr>
					{foreach $valid_nodes as $key => $feature_stories}
						{if gt($col,2)}
							
							{set $col=1}
							{set col=$col|inc}
							</tr><tr>
							
							{else}
								
							{set col=$col|inc}
							{/if}
								
								<td>
								<div class="post">
									<div style="float: left;margin-right: 10px;">{attribute_view_gui attribute=$feature_stories.data_map.image image_class='small' href=$feature_stories.url_alias|ezurl()}</div>
									<h4>{$feature_stories.name}</h4>
									<h3><a href={$feature_stories.url_alias|ezurl()}>{attribute_view_gui attribute=$feature_stories.data_map.headline}</a></h3>
									<h5><a href="#">-{$feature_stories.object.current.creator.name|wash()}</a></h5>
									<div style="height:5px"></div>									
									<p>
									{attribute_view_gui attribute=$feature_stories.data_map.intro}
									
									</p>
									
								</div>
							</td>
							{/foreach}
						</tr>
					</table><!-- #features_table -->
				</div><!-- #features_content -->
			</div><!-- #features -->
		</div><!-- content_features -->	
			
