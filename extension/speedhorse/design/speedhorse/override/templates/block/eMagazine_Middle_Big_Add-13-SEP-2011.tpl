{def $valid_nodes = $block.valid_nodes}
{def $col=1}
{def $icol=1}
{def $dcol=1}
{def $children = array()
                 $children_count = ''}
				 
{def $achid = ''}				 
{set $achid='aa_'}
		<div class="left-content" style="padding-left:25px;" id="myMegazinenew">
			{foreach $valid_nodes as $key => $big_main} 
			 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $big_main.node_id) )}
				{if $children_count}
				<div id="big_{$big_main.node_id}_div" class="newmagazine">
				 <h2>{$big_main.name}</h2><br /><br />

				 	 <div class="featured_products" style="margin-top:5px;margin-left:0px;">
							<div class="mini_nav">
								{set $col=1}
								{set $dcol=1}
								{foreach fetch_alias( 'children', hash( 'parent_node_id', $big_main.node_id) ) as $child }
									{if eq($col,1)}
									<a href="#" class="left_date emagazineactive" id="{$achid}{$child.node_id}_{$dcol}">{$child.name}</a>
									<span class="middle_sep">|</span>
									{elseif lt($col,$children_count)}
									<a href="#" class="mdl_link" id="{$achid}{$child.node_id}_{$dcol}">{$child.name}</a>
									<span class="middle_sep">|</span>
									{else}
									<a href="#" class="right_date" id="{$achid}{$child.node_id}_{$dcol}">{$child.name}</a>
									{/if}
									{set dcol=$dcol|inc}
									{set col=$col|inc}
								{/foreach}
						  </div>
					</div> 
					<br />
					<br />		
					<div id="magazine_items">				
									{set $icol=1}
									{foreach fetch_alias( 'children', hash( 'parent_node_id', $big_main.node_id) ) as $child }
									{if eq($icol,1)}
										<div class="items emagazineactiveimg" id="{$achid}{$child.node_id}_{$icol}_div" >
									{else}
										<div class="items" id="{$achid}{$child.node_id}_{$icol}_div" >
									{/if}
										
										<a href="http://collegeyardart.com/magazines/{attribute_view_gui attribute=$child.data_map.magazine_link}" target="_blank">{attribute_view_gui attribute=$child.data_map.big_image}</a>
										</div>
										{set icol=$icol|inc}
										
									{/foreach}	
					</div>	
				</div>	
				{/if}		
			{/foreach}
								
		</div>
