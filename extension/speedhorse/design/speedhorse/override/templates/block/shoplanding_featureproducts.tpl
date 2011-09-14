{def $col=1}
{def $valid_nodes = $block.valid_nodes}
<div id="shop_right_content">
        	<div class="left-content">
                <h2>{$block.name}</h2>
                <div class="featured_products">
                {foreach $valid_nodes as $key => $shopproduct}	
			   
				{if $col|eq(1)}
					<div class="product">
                    	{attribute_view_gui attribute=$shopproduct.data_map.image image_class=shopfeatureproduct_img href=$shopproduct.url_alias|ezurl()}
                        <h4><a href={$shopproduct.url_alias|ezurl()}>{attribute_view_gui attribute=$shopproduct.data_map.name}</a></h4>
                        <span>{attribute_view_gui attribute=$shopproduct.data_map.short_description}</span>
                        <cite>{attribute_view_gui attribute=$shopproduct.data_map.price}</cite>
                        <a href={$shopproduct.url_alias|ezurl()} class="add_to_cart">Add to Cart</a>
                    </div><!--end div.product-->
                    {elseif $col|eq(2)}
                    <div class="product middle">
                    	{attribute_view_gui attribute=$shopproduct.data_map.image image_class=shopfeatureproduct_img href=$shopproduct.url_alias|ezurl()}
                        <h4><a href={$shopproduct.url_alias|ezurl()}>{attribute_view_gui attribute=$shopproduct.data_map.name}</a></h4>
                        <span>{attribute_view_gui attribute=$shopproduct.data_map.short_description}</span>
                        <cite>{attribute_view_gui attribute=$shopproduct.data_map.price}</cite>
                        <a href={$shopproduct.url_alias|ezurl()} class="add_to_cart">Add to Cart</a>
                    </div><!--end div.product-->
                    {elseif $col|eq(3)}
                   	<div class="product">
                    	{attribute_view_gui attribute=$shopproduct.data_map.image image_class=shopfeatureproduct_img href=$shopproduct.url_alias|ezurl()}
                        <h4><a href={$shopproduct.url_alias|ezurl()}>{attribute_view_gui attribute=$shopproduct.data_map.name}</a></h4>
                        <span>{attribute_view_gui attribute=$shopproduct.data_map.short_description}</span>
                        <cite>{attribute_view_gui attribute=$shopproduct.data_map.price}</cite>
                        <a href={$shopproduct.url_alias|ezurl()} class="add_to_cart">Add to Cart</a>
                    </div><!--end div.product-->
                     {/if}
                     {set col=$col|inc}
                     {if $col|eq(4)}
                       	{set $col=1}
                     {/if}  
            	{/foreach}
             <a href="#" class="view-more">View more Products</a>
                </div><!--end div.featured_products-->
 			</div><!--end div.left-content-->
 </div><!--end div#shop_right_content-->			