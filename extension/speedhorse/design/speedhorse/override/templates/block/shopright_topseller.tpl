{def $valid_nodes = $block.valid_nodes}
<div id="shop_right_content">
        	 <div class="sidebar">
            	 <div class="sellers">
                	<h2>{$block.name}</h2>
                    {foreach $valid_nodes as $key => $shoprighttopseller}	
                    <div class="prod">
                    	{attribute_view_gui attribute=$shoprighttopseller.data_map.image image_class=shoptopseller_img href=$shoprighttopseller.url_alias|ezurl()}
                        <div class="right-info">
                            <h4><a href="{$shoprighttopseller.url_alias|ezurl()}">{attribute_view_gui attribute=$shoprighttopseller.data_map.name}</a></h4>
                            <p>{attribute_view_gui attribute=$shoprighttopseller.data_map.short_description}</p>
                            <cite>{attribute_view_gui attribute=$shoprighttopseller.data_map.price}</cite>
                        </div><!--right-info-->
                    </div><!--end div.prod-->
                    {/foreach}
                </div><!--end div.sellers-->
            </div><!--end div.sidebar-->
 </div><!--end div#shop_right_content-->			