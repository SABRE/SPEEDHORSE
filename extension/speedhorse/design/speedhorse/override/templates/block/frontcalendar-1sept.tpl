{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
<div id="calendar">
				<h2 class="section_title">calendar</h2>
				<ul class="speedhorse_list">
				{foreach $valid_nodes as $key => $event max 4}
					<li><a href={$event.url_alias|ezurl()}>{attribute_view_gui attribute=$event.data_map.short_title}</a></li>
				{/foreach}	
				</ul>
			</div><!--end div#calendar-->
	</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->					
