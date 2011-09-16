{def $col=1}
{def $dcol=1}
{def $ccol=1}
{def $emax=1}
{def $valid_nodes = $block.valid_nodes}
{def $children = array()
                 $children_count = ''}
				 				 
		<div id="magazinemain">
            <div id="accordion_wrap">
				<div id="accordion">
				<!-- "Current magazine" -->
				<h3><a id="curremagazine" href="#">Current Emagazine</a></h3>
					<div id="curremagazine_wrap" style="background:#fffaf4" class="newcurremagazine_wrap">
					{foreach $valid_nodes as $key => $myemagazine max 1}
					{foreach fetch_alias( 'children', hash( 'parent_node_id', $myemagazine.node_id ) ) as $myemagazine_sub reverse}
							<ul style="padding-left:5px;padding-top:2px;margin-right:10px;" class ="mainmagazine">
					 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $myemagazine_sub.node_id) )}
								<li>
								{if eq($col,1)}
								<a href="#" class="myleftclass leftbaractive" id="ul_{$myemagazine_sub.node_id}_div">{$myemagazine_sub.name}</a>
								{else}
								<a href="#" class="myleftclass leftbarinactive" id="ul_{$myemagazine_sub.node_id}_div">{$myemagazine_sub.name}</a>
								{/if}
								{if $children_count}
							
									{if eq($col,1)}
										<ul style="padding-left:5px; padding-top:2px;padding-bottom:2px;" class="subemagazine subemagazineactive" id="subul_{$myemagazine_sub.node_id}_div">
									{else}
									<ul style="padding-left:5px; padding-top:2px;padding-bottom:2px;" class="subemagazine" id="subul_{$myemagazine_sub.node_id}_div">
									{/if}
									{set $dcol=1}
								{foreach fetch_alias( 'children', hash( 'parent_node_id', $myemagazine_sub.node_id) ) as $child }
									
									{if eq($col,1)}
										<li class="subemagazineli subemagazineactive" id="li_{$child.node_id}_{$dcol}" ><a href="#" style="margin-left:25px;">{attribute_view_gui attribute=$child.data_map.small_image }{$child.name}</a></li>
									{else}
										<li class="subemagazineli" id="li_{$child.node_id}_{$dcol}" ><a href="#" style="margin-left:25px;">{attribute_view_gui attribute=$child.data_map.small_image }{$child.name}</a></li>	
									{/if}
									
									{set dcol=$dcol|inc}
									{/foreach}
									</ul>
								{/if}
								</li>
								{set col=$col|inc}
							</ul>
							{/foreach}
						{/foreach}	
					</div><!--end div#current magazine-->
					
					<!-- "Archive magazine" -->
					<h3><a id="archivedemagazine" href="#">Archived Emagazine</a></h3>
					
					<div id="aremagazine_wrap" style="background:#fffaf4;height:auto;" class="newaremagazine_wrap">
						
						{foreach $valid_nodes as $key => $myemagazine}
						
							{if eq($key,0)}
							
							{else}
							
								{foreach fetch_alias( 'children', hash( 'parent_node_id', $myemagazine.node_id ) ) as $myemagazine_sub reverse}
							<ul style="padding-left:5px;padding-top:2px;margin-right:10px;" class ="mainmagazine">
							 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $myemagazine_sub.node_id) )}
								<li>
								{if eq($col,1)}
								<a href="#" class="myleftclass leftbaractive" id="ul_{$myemagazine_sub.node_id}_div">{$myemagazine_sub.name}</a>
								{else}
								<a href="#" class="myleftclass leftbarinactive" id="ul_{$myemagazine_sub.node_id}_div">{$myemagazine_sub.name}</a>
								{/if}
								{if $children_count}
							
									{if eq($col,1)}
										<ul style="padding-left:5px; padding-top:2px;padding-bottom:2px;" class="subemagazine subemagazineactive" id="subul_{$myemagazine_sub.node_id}_div">
									{else}
									<ul style="padding-left:5px; padding-top:2px;padding-bottom:2px;" class="subemagazine" id="subul_{$myemagazine_sub.node_id}_div">
									{/if}
									{set $dcol=1}
								{foreach fetch_alias( 'children', hash( 'parent_node_id', $myemagazine_sub.node_id) ) as $child }
									
									{if eq($col,1)}
										<li class="subemagazineli subemagazineactive" id="li_{$child.node_id}_{$dcol}" ><a href="#" style="margin-left:25px;">{attribute_view_gui attribute=$child.data_map.small_image }{$child.name}</a></li>
									{else}
										<li class="subemagazineli" id="li_{$child.node_id}_{$dcol}" ><a href="#" style="margin-left:25px;">{attribute_view_gui attribute=$child.data_map.small_image }{$child.name}</a></li>	
									{/if}
									
									{set dcol=$dcol|inc}
									{/foreach}
									</ul>
								{/if}
								</li>
								{set col=$col|inc}
							</ul>
							{/foreach}
						
						{/foreach}
							
					</div><!--end div#current magazine-->

				</div><!--end div#accordion -->
			</div><!--end div#left_sidebar_content-->
		 </div>         