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
        	<h2 class="product">{$node.name|wash()}<span class="cart"><a href="#">My Cart (2)</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="#">Checkout</a></span></h2>
            <div id="product_top">
            	<div id="product_images_wrap">
            		<div id="product_images">
                    	<div id="main_img">
						  <a href="#" id="image-1-a-img">{attribute_view_gui image_class=product_img attribute=$node.data_map.image}</a>
   <a href="#" style="display:none;" id="image-2-a-img">{attribute_view_gui image_class=product_img attribute=$node.data_map.image1}</a>
   <a href="#" style="display:none;" id="image-3-a-img">{attribute_view_gui image_class=product_img attribute=$node.data_map.image2}</a>
   <a href="#" style="display:none;" id="image-4-a-img">{attribute_view_gui image_class=product_img attribute=$node.data_map.image3}</a>
   <a href="#" style="display:none;" id="image-5-a-img">{attribute_view_gui image_class=product_img attribute=$node.data_map.image4}</a>
                        </div><!--end id#main_img-->
                        <div id="images">
            				<a href="#" id="image-1-a">{attribute_view_gui attribute=$node.data_map.small_image}</a>
                            <a href="#" id="image-2-a">{attribute_view_gui attribute=$node.data_map.small_image1}</a>
                            <a href="#" id="image-3-a">{attribute_view_gui attribute=$node.data_map.small_image2}</a>
             				<a href="#" id="image-4-a">{attribute_view_gui attribute=$node.data_map.small_image3}</a>
							<a href="#" id="image-5-a">{attribute_view_gui attribute=$node.data_map.small_image4}</a>
                        </div><!--end id#images-->
                	</div><!--end div#product_images-->
                </div><!--end div#product_images_wrap-->
                                <div id="product_buy_wrap"> 
                	<div id="product_buy"> 
                    	<h3 class="price">{attribute_view_gui attribute=$node.object.data_map.price}</h3> 
                       <form method="post" action={"content/action"|ezurl}>
                            <div > 
							           {attribute_view_gui attribute=$node.object.data_map.additional_options}
                            </div><!--end div#option--> 
							<div class="option">
                                <span>QTY</span>
                                 <input type="text" name="Quantity" value="1" />
                            </div><!--end div#option-->
<input type="submit" class="defaultbutton" name="ActionAddToBasket" id="add_to_cart" value="{"Add to basket"|i18n("design/ezwebin/full/product")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
                         
                	</div><!--end div#product_buy--> 
                </div><!--end div#product_buy_wrap--> 

            </div><!--end div#product_top-->
            <div id="product_details">
            	<h3>{$node.name|wash()}&nbsp;DETAILS</h3>
                <p>{attribute_view_gui attribute=$node.object.data_map.description}</p>
            </div><!--end div#product_details-->
			  {def $related_purchase=fetch( 'shop', 'related_purchase', hash( 'contentobject_id', $node.object.id, 'limit', 4 ) )}
{def $col=1}
 			<div id="related_products">
            	<h3>Related Products</h3>
			   {if $related_purchase}
                <div id="products_list">
					{foreach $related_purchase as $product}	
					{if $col|lt(4)}	
						{def $related_info=fetch( 'content', 'object', hash( 'object_id', $product.id ) )}
                	<div class="prod">
					
				        {attribute_view_gui attribute=$related_info.data_map.image image_class=shopfeatureproduct_img} 
					
                        <h4><a href="#">{content_view_gui view=text_linked content_object=$product}</a></h4>
						
						<span>{attribute_view_gui attribute=$related_info.data_map.short_description}</span>
                        <span style="padding:0 0 0 25px;">{attribute_view_gui attribute=$related_info.data_map.price}</span>
                    </div><!--end div.prod-->
					{undef $related_info}
					{else}
					{def $related_info=fetch( 'content', 'object', hash( 'object_id', $product.id ) )}
                	<div class="prod last">
                    	{attribute_view_gui image_class=shopfeatureproduct_img attribute=$product.data_map.image}
                        <h4><a href="#">{content_view_gui view=text_linked content_object=$product}</a></h4>
						
						<span>{attribute_view_gui attribute=$related_info.data_map.short_description}</span>
                        <span style="padding:0 0 0 25px;">{attribute_view_gui attribute=$related_info.data_map.price}</span>
                    </div><!--end div.prod-->
					{/if}
					{set col=$col|inc}
					{/foreach}
					{undef $related_info}
                </div><!--end div#products_list-->
				{/if}
				{undef $related_purchase}				
            </div><!--end div#related_products-->
        </div><!--end div#right_content-->
		</form>
	</div>
</div>
