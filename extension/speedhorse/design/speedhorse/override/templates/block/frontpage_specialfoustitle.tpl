{def $valid_nodes = $block.valid_nodes}


			<div id="left_bottom_panel">
					<h2 class="section_title">{$block.name}</h2>
					<ul class="speedhorse_list">
					{foreach $valid_nodes as $key => $race_specialfocustitle}
						<li><a href={$race_specialfocustitle.url_alias|ezurl()}>{attribute_view_gui attribute=$race_specialfocustitle.data_map.title}</a></li>
					{/foreach}		
					</ul>
				</div><!-- #left_bottom_panel -->

