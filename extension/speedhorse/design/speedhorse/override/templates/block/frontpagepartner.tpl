{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
<div id="upper_sidebar_ads">
				
				<div id="featured_partners_divider" style="height:18px; margin-bottom:5px; overflow:hidden;">
					<div style="width:28%; float:left; margin-top:3px;"><hr style="height:1px; border:none; background:#EBE4D4;"></div>
					<div style="width:44%; float:left; font-family:Gotham-Medium; font-size:10px; color:#23436A; text-align:center;">FEATURED PARTNERS</div>
					<div style="width:28%; float:left; margin-top:3px;"><hr style="height:1px; border:none; background:#EBE4D4"></div>
				</div><!--end div#featured_partners_divider-->
				<div>
						{foreach $valid_nodes as $key => $partner}
						<div class="featured_partners">{attribute_view_gui attribute=$partner.data_map.partner_image image_class=partner_img href=$partner.url_alias|ezurl()}</div>
						{/foreach}
					
				</div>
				
				<div style="clear:both;"></div>
			</div><!--end div#upper_sidebar_ads-->
		</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->		

