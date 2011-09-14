{def $col=1}
{def $valid_nodes = $block.valid_nodes}
<div id="class_left">
            <div id="class_ads">
            	<h2>{$block.name}</h2>
                <div id="class_ads_wrap">
				{foreach $valid_nodes as $key => $classifyad}
              
                  	{def $chk1=$col|mod(2)}
					{if $chk1|eq(0)}
                	<div class="ad">
                    	{attribute_view_gui attribute=$classifyad.data_map.image image_class=artical_img href=$classifyad.url_alias|ezurl()}
                        <div class="right-info">
                        	<h4><a href={$classifyad.url_alias|ezurl()}>{attribute_view_gui attribute=$classifyad.data_map.headline}</a></h4>
                            <cite>{attribute_view_gui attribute=$classifyad.data_map.city}, {attribute_view_gui attribute=$classifyad.data_map.state}</cite>
                            <span>{attribute_view_gui attribute=$classifyad.data_map.price}</span>
                        </div><!--end div.right-info-->
                    </div><!--end div.ad-->
					{else}
                    <div class="ad alt">
                    	{attribute_view_gui attribute=$classifyad.data_map.image image_class=artical_img href=$classifyad.url_alias|ezurl()}
                        <div class="right-info">
                        	<h4><a href={$classifyad.url_alias|ezurl()}>{attribute_view_gui attribute=$classifyad.data_map.headline}</a></h4>
                            <cite>{attribute_view_gui attribute=$classifyad.data_map.city}, {attribute_view_gui attribute=$classifyad.data_map.state}</cite>
                            <span>{attribute_view_gui attribute=$classifyad.data_map.price}</span>
                        </div><!--end div.right-info-->
                    </div><!--end div.ad-->
					{/if}
			  		{set col=$col|inc}
				{/foreach}
               </div><!--end div#class_ads_wrap-->
            </div><!--end div#class_ads-->
        </div><!--end div#class_left-->