{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
<div id="upper_sidebar_ads">
				
				
				{foreach $valid_nodes as $key => $smalladd}
				<div id="big_ad_1">{attribute_view_gui attribute=$smalladd.data_map.ad_image image_class='small_advertisment'  href=$smalladd.url_alias|ezurl() }</div>
				{/foreach}	
				
				
				<div style="clear:both;"></div>
			</div><!--end div#upper_sidebar_ads-->
		</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->		

