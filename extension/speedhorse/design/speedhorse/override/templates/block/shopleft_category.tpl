{def $valid_nodes = $block.valid_nodes} 
{def $children = array()
 $children_count = ''}       
		<div id="left_sidebar_wrap_large">
        	<h3 class="categories">{$block.name}</h3>
            <div id="left_sidebar">
                <div id="left_sidebar_content">
                  {foreach $valid_nodes as $key => $shopcat} 
				    <div class="info active">
                        <h3>{attribute_view_gui attribute=$shopcat.data_map.name}</h3>
                        {if $shopcat.data_map.show_children}
	                       {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $shopcat.node_id) )}
                       {/if}   
                       {if $children_count}
        			              <ul class="large">
                    			    {foreach fetch_alias( 'children', hash( 'parent_node_id', $shopcat.node_id) ) as $child }
                           				<li></li><a href={$child.url_alias|ezurl()}>{$child.name}</a><br />
                        			{/foreach}
                   					</ul>
                		{/if}       
                    </div><!--end div.info-->
					{/foreach}
                </div><!--end div#left_sidebar_content-->
            </div><!--end div#left_sidebar-->
        </div><!--end div#left_sidebar_wrap_large-->