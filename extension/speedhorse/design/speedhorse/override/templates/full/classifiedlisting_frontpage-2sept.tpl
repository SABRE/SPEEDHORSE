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
                 	{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '190') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '190')  ) as $child}
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
        	<h2>{attribute_view_gui attribute=$node.data_map.title}</h2>
            <div class="info_bar">
            	<div class="left-area">
                	<a href="#" class="print">Print</a>
                    <a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Email'|i18n( 'design/ezwebin/full/article' )}" class="email">Email</a>
                </div><!--end div.left-area-->
                <div class="social">
                	<img src="../images/fb_like.jpg" />
                    <img src="../images/fb_people_like.jpg" />
                    <img src="../images/twitter.jpg" />
                </div><!--end div.social-->
            </div><!--end div.info_bar-->
            <div id="content_wrap">
            	<div id="sidebar_content">
                	<div id="image_container">
                    	<div id="main_img">
                			<a href="#">{attribute_view_gui attribute=$node.data_map.image}</a>
                        </div><!--end div#main_img-->
                        <div id="images">
                        	<a href="#">{attribute_view_gui attribute=$node.data_map.image}</a>
                            <a href="#">{attribute_view_gui attribute=$node.data_map.image1}</a>
                            <a href="#">{attribute_view_gui attribute=$node.data_map.image2}</a>
                            <a href="#">{attribute_view_gui attribute=$node.data_map.image3}</a>
                        </div><!--end div#images-->
                    </div><!--end div#image_container-->
                    <div class="contact_info">
                    	<h3>Contact Information</h3>
                        <span>{attribute_view_gui attribute=$node.data_map.advertiser_name}</span>
                        <span><a href="mailto:email@domain.com">{attribute_view_gui attribute=$node.data_map.email}</a></span>
                        <span>{attribute_view_gui attribute=$node.data_map.phone_no}</span>
                    </div><!--end div.contact_info-->
                    <div class="contact_info">
                    	<h3>Listing Tools</h3>
                        <span><a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Email a Friend'|i18n( 'design/ezwebin/full/article' )}">Email a Friend</a></span>
                        <span><a href="#">Print this Listing</a></span>
                        
                    </div><!--end div.contact_info-->
                </div><!--end div#sidebar_content-->
                <div id="main_content">
                	<h3>{attribute_view_gui attribute=$node.data_map.city},{attribute_view_gui attribute=$node.data_map.state} &nbsp;&nbsp; <span>{attribute_view_gui attribute=$node.data_map.price}</span></h3>
                	<p>{attribute_view_gui attribute=$node.data_map.body}</p>
                </div><!--end div#main_content-->
            </div><!--end div#content_wrap-->
        </div><!--end div#right_content-->
        
   </div>
</div>        