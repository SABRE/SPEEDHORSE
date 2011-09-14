{def $valid_nodes = $block.valid_nodes}
					{foreach $valid_nodes as $key => $big_add}
			<div id="big_ad_250" style="margin-top:0;float:right;">
			{attribute_view_gui attribute=$big_add.data_map.ad_image image_class=big_add href=$big_add.url_alias|ezurl()}
			</div>
{/foreach}