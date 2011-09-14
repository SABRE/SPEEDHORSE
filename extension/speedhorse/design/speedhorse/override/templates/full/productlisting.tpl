 <div style="width:940px;">
	<div style="width:200px;float:left">
		<div id="left_sidebar">
        	<div id="left_sidebar_content">
            	<div class="info">
                	<h3>Price</h3>
                    <form action="#" method="post" id="price_search">
                        <input type="text" name="search" value="">
                        <span>TO</span>
                        <input type="text" name="search" value="">
                        <input type="submit" style="display:none;">
                    </form>
                </div><!--end div.info-->
                <div class="info">
                	<h3>Search</h3>
                    <form action="#" method="post" id="price_search">
                        <input type="text" name="search" value="" style="width:170px;">
                        <input type="submit" style="display:none;">
                    </form>
                </div><!--end div.info-->
                <div class="info">
                {def $children = array()
                 	$children_count = ''}
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '166') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '166')  ) as $child}
                                 <div class="info">
                          				<h3>{attribute_view_gui attribute=$child.data_map.name}</h3>
                                       	{def $children1 = array()
                 								$children_count1 = ''}
                 								
                                                
                                                {set $children_count1=fetch_alias( 'children_count', hash( 'parent_node_id', $child.node_id) )}
       				 							{if $children_count1}
                                                	<ul>
                                                    {foreach fetch_alias( 'children', hash( 'parent_node_id', $child.node_id)  ) as $child1}
                                                        <br /><li></li><a href={$child1.url_alias|ezurl()}>{attribute_view_gui attribute=$child1.data_map.name}</a>
                                                    {/foreach}
                                                     </ul>
       											{/if} 
                 			 </div><!--end div.info-->                                 
                   			{/foreach}
       				{/if} 
                </div><!--end div.info-->
            </div><!--end div#left_sidebar_content-->
        </div><!--end div#left_sidebar-->
	</div>
	<div style="float:right">
        <div id="right_content">
    		<h2>{attribute_view_gui attribute=$node.data_map.name}</h2>
               <div id="listings">
				{foreach fetch_alias( 'children', hash( 'parent_node_id', $node.node_id)) as $newchild}
                                        <div class="listing">
                                            <div class="listing-head">
                                           		<h3><span>{attribute_view_gui attribute=$newchild.data_map.name}</span></h3>
                                                <span class="info-top">
                                                 <span class="price">{attribute_view_gui attribute=$newchild.data_map.price}</span>
                                                </span><!--end span.info-top-->
                                            </div><!--end div.listing-head-->
                                                <div class="listing-foot">
                                                    {attribute_view_gui attribute=$newchild.data_map.image image_class=shopfeatureproduct_img href=$newchild.url_alias|ezurl()}

                                                    {attribute_view_gui attribute=$newchild.data_map.description}<p style="float:right;"><a href={$child2.url_alias|ezurl()}>More Details &raquo;</a></p>
                                                </div><!--end div.listing-foot-->
                                            </div><!--end div.listing-->
                                {/foreach}
       				
            </div><!--end div#listings-->
        </div><!--end div#right_content-->
	</div>
</div>