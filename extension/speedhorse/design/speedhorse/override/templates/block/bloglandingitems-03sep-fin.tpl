{def $col=1}
{def $valid_nodes = $block.valid_nodes}

<div id="content"><!--start div#content-->                      
            <div class="category_posts">
            {foreach $valid_nodes as $key => $bloglandingitem}	
			{if gt($col,1)}
				{set $col=1}

            	<div class="post" style="margin-right:20px;">
                	{attribute_view_gui attribute=$bloglandingitem.data_map.image image_class='small_blog' href=$bloglandingitem.url_alias|ezurl()}
                    <div class="post_right">
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.name}{*attribute_view_gui attribute=$bloglandingitem.data_map.title*}</a></h4>
                       
                        <p>{attribute_view_gui attribute=$bloglandingitem.data_map.description}</p>
                        
                    </div><!--end div.post_right-->

                </div><!--end div.post-->
				{else}
                <div class="post">
                	{attribute_view_gui attribute=$bloglandingitem.data_map.image image_class='small_blog' href=$bloglandingitem.url_alias|ezurl()}
                    <div class="post_right">
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.name}{*attribute_view_gui attribute=$bloglandingitem.data_map.title*}</a></h4>
                       
                        <p>{attribute_view_gui attribute=$bloglandingitem.data_map.description}</p>
                        
                    </div><!--end div.post_right-->
                </div><!--end div.post-->
				{set col=$col|inc}
			{/if}	

			{/foreach}

            </div><!--end div.category_posts-->

		</div><!--end div#content-->