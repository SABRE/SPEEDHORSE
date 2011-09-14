{def $valid_nodes = $block.valid_nodes}
<div id="shop_right_content">
        	<div class="left-content">
            {foreach $valid_nodes as $key => $shopproducttop}
                <div class="featured_product">
                	<div class="main_img">{attribute_view_gui attribute=$shopproducttop.data_map.image image_class=shopfeaturetop_img href=$shopproducttop.url_alias|ezurl()}</div>
                    <h3>{attribute_view_gui attribute=$shopproducttop.data_map.name}</h3>
                    <p>{attribute_view_gui attribute=$shopproducttop.data_map.short_description}</p>
                    <span>{attribute_view_gui attribute=$shopproducttop.data_map.price}</span>
                </div><!--end div.featured_product-->
              {/foreach}   
 			</div><!--end div.left-content-->
 </div><!--end div#shop_right_content-->			