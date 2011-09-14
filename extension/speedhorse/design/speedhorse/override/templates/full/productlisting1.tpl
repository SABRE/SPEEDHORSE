<div style="width:940px">
	<div style="float:left; width:200px;">
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
      </div><!--end div-->
	  
    <div style="float:right">
        <div id="right_content">
		<h2>{attribute_view_gui attribute=$node.data_map.name}</h2>
            <div id="content_wrap">
            	<div id="sidebar_content">
				{foreach fetch_alias( 'children', hash( 'parent_node_id', $node.node_id)) as $newchild}
                		<h3><span>{attribute_view_gui attribute=$newchild.data_map.name}</span></h3>
                    	<div id="main_img">
							<a href="#">{attribute_view_gui attribute=$newchild.data_map.image}</a>
                        </div><!--end div#main_img-->
						<div class="post">
							<p>{attribute_view_gui attribute=$newchild.data_map.short_description}</p>
	                		<h3><span>{attribute_view_gui attribute=$newchild.data_map.price}</span></h3>
						</div>	
			    {/foreach}
                </div><!--end div#sidebar_content-->
            </div><!--end div#content_wrap-->
        </div><!--end div#right_content-->
   </div><!-- div-->
   
</div>        