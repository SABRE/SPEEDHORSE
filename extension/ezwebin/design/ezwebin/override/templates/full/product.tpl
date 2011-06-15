{* Product - Full view *}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<form method="post" action={"content/action"|ezurl}>
<div class="content-view-full">
    <div class="class-product">

        <div class="attribute-header">
        <h1>{$node.name|wash()}</h1>
        </div>
        
        {if $node.data_map.image.has_content}
        <div class="attribute-image">
            {attribute_view_gui image_class=medium attribute=$node.data_map.image}
            {if $node.data_map.caption.has_content}
            <div class="caption">
                {attribute_view_gui attribute=$node.data_map.caption}
            </div>
            {/if}
        </div>
        {/if}

        <div class="attribute-product-number">
           {attribute_view_gui attribute=$node.object.data_map.product_number}
        </div>

        <div class="attribute-short">
           {attribute_view_gui attribute=$node.object.data_map.short_description}
        </div>

        <div class="attribute-long">
           {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <div class="attribute-price">
          <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
          </p>
        </div>

        <div class="attribute-multi-options">
           {attribute_view_gui attribute=$node.object.data_map.additional_options}
        </div>

        {* Category. *}
        {def $product_category_attribute=ezini( 'VATSettings', 'ProductCategoryAttribute', 'shop.ini' )}
        {if and( $product_category_attribute, is_set( $node.data_map.$product_category_attribute ) )}
        <div class="attribute-long">
          <p>Category:&nbsp;{attribute_view_gui attribute=$node.data_map.$product_category_attribute}</p>
        </div>
        {/if}
        {undef $product_category_attribute}

        <div class="content-action">
            <input type="submit" class="defaultbutton" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/ezwebin/full/product")}" />
            <input class="button" type="submit" name="ActionAddToWishList" value="{"Add to wish list"|i18n("design/ezwebin/full/product")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </div>

       {* Related products. *}
       {def $related_purchase=fetch( 'shop', 'related_purchase', hash( 'contentobject_id', $node.object.id, 'limit', 10 ) )}
       {if $related_purchase}
        <div class="relatedorders">
            <h2>{'People who bought this also bought'|i18n( 'design/ezwebin/full/product' )}</h2>

            <ul>
            {foreach $related_purchase as $product}
                <li>{content_view_gui view=text_linked content_object=$product}</li>
            {/foreach}
            </ul>
        </div>
       {/if}
       {undef $related_purchase}
   </div>
</div>


		<div id="right_content">
<h2 class="product">Product Name Goes Here<span class="cart">My Cart (2)&nbsp;&nbsp;|&nbsp;&nbsp; Checkout</span></h2>
            <div id="product_top">
            	<div id="product_images_wrap">
            		<div id="product_images">
                    	<div id="main_img">
                        	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/product_placeholder.jpg" />
                        </div><!--end id#main_img-->
                        <div id="images">
                        	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/product_placeholder_small.jpg" class="first" />
                            <img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/product_placeholder_small.jpg" />
                            <img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/product_placeholder_small.jpg" />
                            <img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/product_placeholder_small.jpg" class="last" />
                        </div><!--end id#images-->
                	</div><!--end div#product_images-->
                </div><!--end div#product_images_wrap-->
                <div id="product_buy_wrap">
                	<div id="product_buy">
                    	<h3 class="price">$500.00&nbsp;&nbsp;<strike>$650.00</strike></h3>
                        <form action="#" method="post" id="buy_product">
                            <div class="option">
                                <span>Color</span>
                                <select name="color">
                                	<option value="blue">Blue</option>
                                	<option value="red">Red</option>
                                </select>
                            </div><!--end div#option-->
                            <div class="option">
                                <span>Size</span>
                                <select name="size">
                                	<option value="Large">Large</option>
                                	<option value="medium">Medium</option>
                                </select>
                            </div><!--end div#option-->
                            <div class="option">
                                <span>QTY</span>
                                <input type="text" name="quantity" value="1" />
                            </div><!--end div#option-->
                            <input type="submit" id="add_to_cart" value="Add to Cart" />
                        </form>
                	</div><!--end div#product_buy-->
                </div><!--end div#product_buy_wrap-->
            </div><!--end div#product_top-->
            <div id="product_details">
            	<h3>PRODUCT DETAILS</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque luctus egestas nibh a pharetra. Donec luctus ligula ut nulla vehicula a convallis ligula mollis. Praesent justo nibh, fringilla a pharetra id, elementum non lacus. Morbi et nisl orci. Duis nec nibh magna. Quisque vel tellus dui, vel semper nulla. Praesent in diam risus, vel pulvinar dolor. Donec iaculis mauris sit amet odio pharetra non ultricies massa mollis. Proin vulputate neque quis erat condimentum sed ultrices lorem tincidunt. Proin rhoncus convallis mi, at vulputate nunc sodales ac. Proin nunc dolor, porta vitae ornare eget, feugiat id arcu.</p>
            </div><!--end div#product_details-->
            <div id="related_products">
            	<h3>Related Products</h3>
                <div id="products_list">
                	<div class="prod">
                    	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/prod_placeholder.jpg">
                        <h4>Product Name</h4>
                        <span>Lorem ipsum dolor sit adipiscing elit...</span>
                        <cite>$98.95</cite>
                    </div><!--end div.prod-->
                    <div class="prod">
                    	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/prod_placeholder.jpg">
                        <h4>Product Name</h4>
                        <span>Lorem ipsum dolor sit adipiscing elit...</span>
                        <cite>$98.95</cite>
                    </div><!--end div.prod-->
                    <div class="prod">
                    	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/prod_placeholder.jpg">
                        <h4>Product Name</h4>
                        <span>Lorem ipsum dolor sit adipiscing elit...</span>
                        <cite>$98.95</cite>
                    </div><!--end div.prod-->
                    <div class="prod last">
                    	<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/prod_placeholder.jpg">
                        <h4>Product Name</h4>
                        <span>Lorem ipsum dolor sit adipiscing elit...</span>
                        <cite>$98.95</cite>
                    </div><!--end div.prod-->
                </div><!--end div#products_list-->
            </div><!--end div#related_products-->
		</div><!--end div#right_content-->
</form>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>