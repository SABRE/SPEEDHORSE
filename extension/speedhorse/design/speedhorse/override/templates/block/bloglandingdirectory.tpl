{def $valid_nodes = $block.valid_nodes}
<div id="blog_directory" style="border-bottom:none;margin-bottom:0;">
            	<h4>Blog Directory:</h4>
				{foreach $valid_nodes as $key => $bloglandingdirectory}
                <a href="#">{attribute_view_gui attribute=$bloglandingdirectory.data_map.name}</a>
				{/foreach}
</div><!--end div#blog_directory-->