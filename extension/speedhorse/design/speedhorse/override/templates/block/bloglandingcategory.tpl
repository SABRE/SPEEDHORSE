{def $valid_nodes = $block.valid_nodes}
    <div class="category_posts">
    {foreach $valid_nodes as $key => $bloglandingcategory}	
		<h3 class="category_title">
       	<a href="#">{attribute_view_gui attribute=$bloglandingcategory.data_map.name}</a>
        <span><a href="#header">Back to top</a></span>
       	</h3><!--end h3.category_title-->
    {/foreach}	
    </div><!--end div.category_posts-->