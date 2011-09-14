{def $col=1}
{def $valid_nodes = $block.valid_nodes}
{def $children = array()
                 $children_count = ''}
                 
<div id="class_left">
            <div id="class_cats">
			{foreach $valid_nodes as $key => $classifycat}	
            
			   	{def $chk=$col|mod(2)}
				{if $chk|eq(0)}
				<ul class="main">
					<li class="main">
                     {if $classifycat.data_map.show_children}
	                       {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $classifycat.node_id) )}
                       {/if}   
                    	<!--<a href={$classifycat.url_alias|ezurl()} class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>
                       aa{$classifycat.node_id}aa-->
                                                                 
		                       {if $children_count}
                               <a href="#" class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>
        			               <ul class="sub-cats">
                    			    {foreach fetch_alias( 'children', hash( 'parent_node_id', $classifycat.node_id) ) as $child }
                           				<li><a href={$child.url_alias|ezurl()} >{$child.name}</a></li>
                        			{/foreach}
                   					</ul>
                                 {else}
                                 <a href={$classifycat.url_alias|ezurl()} class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>   
                				{/if}                                               
                       
                    </li>
                </ul>
				{else}
                <ul class="right main">
                	<li class="main">
                    {if $classifycat.data_map.show_children}
	                       {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $classifycat.node_id) )}
                       {/if}   
                    	<!--<a href={$classifycat.url_alias|ezurl()} class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>-->
                         {if $children_count}
                         <a href="#" class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>
        			               <ul class="sub-cats">
                    			    {foreach fetch_alias( 'children', hash( 'parent_node_id', $classifycat.node_id) ) as $child }
                           				<li><a href={$child.url_alias|ezurl()} >{$child.name}</a></li>
                        			{/foreach}
                   					</ul>
                           {else}
                           <a href={$classifycat.url_alias|ezurl()} class="main">{attribute_view_gui attribute=$classifycat.data_map.name}[{$children_count}]</a>                   		{/if}        
                    </li>
                </ul>
			  {/if}
			  {set col=$col|inc}
		{/foreach}
            </div><!--end div#class_cats-->
</div><!--end div#class_left-->