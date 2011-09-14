{def $valid_nodes = $block.valid_nodes}
					{foreach $valid_nodes as $key => $small_add}
<div id="big_ad_100" style="margin-top:0;float:right;">
{attribute_view_gui attribute=$small_add.data_map.ad_image image_class='small_add' href=$small_add.url_alias|ezurl()}
</div>			
{/foreach}