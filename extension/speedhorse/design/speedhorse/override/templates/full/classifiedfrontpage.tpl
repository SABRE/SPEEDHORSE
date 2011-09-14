<div style="width:940px">
	<div style="float:left; width:200px;">
<div id="left_sidebar">
        	<div id="left_sidebar_content">
            	<div class="info">
                	<h3>Price</h3>
                    <form action="#" method="post" id="price_search">
                        <input type="text" name="search" value="$">
                        <span>TO</span>
                        <input type="text" name="search" value="$">
                        <input type="submit" style="display:none;">
                    </form>
                </div><!--end div.info-->
                
                
                {def $children = array()
                 	$children_count = ''}
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '580') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '580')  ) as $child}
                                 <div class="info active">
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

             
                
            </div><!--end div#left_sidebar_content-->
        </div><!--end div#left_sidebar-->
    </div>
    <div style="float:right">
           <div id="right_content">
        	<h2>{attribute_view_gui attribute=$node.data_map.name}<span class="right-info"><a href="#">Reset Filters</a></span></h2>
            <div id="search_listings">
            	<h3>Search</h3>
                <form action="#" method="post">
                	<div>
                    	<select name="" id="bars">
                        	<option vlaue="">Bars and Pubs</option>
                        </select>
                        <span id="bars-and">and</span>
                        <select name="" id="local">
                        	<option vlaue="">Select a Location</option>
                        </select>
                        <span id="local-and">and</span>
                        <input type="text" value="Keywords" id="keywords" />
                        <input type="submit" id="submit" value="" />
                    </div>
                </form>
            </div><!--end div#search_listings-->
            <div id="listings">
           
            {def $children2 = array()
                 $children_count2 = ''}
                 {set $children_count2=fetch_alias( 'children_count', hash( 'parent_node_id',$node.node_id ) )}
       				 {if $children_count2}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', $node.node_id)  ) as $child2 }
                           				
                                        <div class="listing">
                                                <div class="listing-head">
                                                    <a href={$child2.url_alias|ezurl()}>{attribute_view_gui attribute=$child2.data_map.title}</a>
                                                    <span class="info-top">
                                                        {attribute_view_gui attribute=$child2.data_map.city},{attribute_view_gui attribute=$child2.data_map.state}
                                                        <span class="price">{attribute_view_gui attribute=$child2.data_map.price}</span>
                                                    </span><!--end span.info-top-->
                                                </div><!--end div.listing-head-->
                                                <div class="listing-foot">
                                                    {attribute_view_gui attribute=$child2.data_map.image image_class=classify_listing_img href=$child2.url_alias|ezurl()}
                                                    {attribute_view_gui attribute=$child2.data_map.summary}<p style="float:right;"><a href={$child2.url_alias|ezurl()}>More Details &raquo;</a></p>
                                                </div><!--end div.listing-foot-->
                                            </div><!--end div.listing-->
                                {/foreach}
                                 <a href="#" class="view-more">View More</a>
       				{/if} 
                
            </div><!--end div#listings-->
        </div><!--end div#right_content-->
</div>
</div><!--- Closing of div 940px -->        