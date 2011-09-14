{def $valid_nodes = $block.valid_nodes}

				<div id="products_preview" class="post"><!--start div#products_preview-->
					<h2 class="section_title">{$block.name}</h2>
					{foreach $valid_nodes as $key => $fromshop}
                    <div>
						{attribute_view_gui attribute=$fromshop.data_map.image image_class=frontproduct_img href=$fromshop.url_alias|ezurl()}
						<h3><a href={$fromshop.url_alias|ezurl()}>{attribute_view_gui attribute=$fromshop.data_map.name}</a></h3>
						<h6><a href={$fromshop.url_alias|ezurl()}>{attribute_view_gui attribute=$fromshop.data_map.product_category}</a></h6>
						<span>{attribute_view_gui attribute=$fromshop.data_map.price}</span>
					</div>
                    {/foreach}
				</div><!--end div#products_preview -->
			