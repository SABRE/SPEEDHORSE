{def $valid_nodes = $block.valid_nodes}
<div id="shop_right_content">
        	 <div class="sidebar">
              {foreach $valid_nodes as $key => $shoprightproduct}	
            	<div class="featured">
                	<h2>{$block.name}</h2>
                    {attribute_view_gui attribute=$shoprightproduct.data_map.image image_class=shopfeatureproductright_img href=$shoprightproduct.url_alias|ezurl()}
                    <h3><a href="{$shoprightproduct.url_alias|ezurl()}">{attribute_view_gui attribute=$shoprightproduct.data_map.name}</a></h3>
                    <p>{attribute_view_gui attribute=$shoprightproduct.data_map.short_description}</p>
                    <cite>{attribute_view_gui attribute=$shoprightproduct.data_map.price}</cite>
                </div><!--end div.featured-->
                {/foreach}
            </div><!--end div.sidebar-->
 </div><!--end div#shop_right_content-->			