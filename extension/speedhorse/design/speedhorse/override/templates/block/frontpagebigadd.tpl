{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
			{foreach $valid_nodes as $key => $bigadd}<div id="big_ad_2">{attribute_view_gui attribute=$bigadd.data_map.ad_image image_class=bigadd_img href=$bigadd.url_alias|ezurl()}</div>{/foreach}	
	</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->		

