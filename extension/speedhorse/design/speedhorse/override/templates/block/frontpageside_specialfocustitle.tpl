{def $col=1}
{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
<h2 class="section_title">{$block.name}</h2>
			<ul class="speedhorse_list">
			{foreach $valid_nodes as $key => $specialfocustitle}
				<li><a href={$specialfocustitle.url_alias|ezurl()}>{attribute_view_gui attribute=$specialfocustitle.data_map.title}</a></li>
			{/foreach}	
			</ul>
			
	</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->								
