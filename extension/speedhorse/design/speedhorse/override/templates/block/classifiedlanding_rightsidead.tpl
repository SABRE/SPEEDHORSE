{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
<div id="listings_wrap">
            	<h3 class="main"><a href="#"><img src="../images/class_post.jpg" alt="#" /></a></h3>
                {foreach $valid_nodes as $key => $classifysideadlisting}	
				<div class="listing">
                	<h4 class="blue">{attribute_view_gui attribute=$classifysideadlisting.data_map.title}</h4>
                    <h3><a href={$classifysideadlisting.url_alias|ezurl()}>{attribute_view_gui attribute=$classifysideadlisting.data_map.headline}</a></h3>
                    <h4>{attribute_view_gui attribute=$classifysideadlisting.data_map.city}, {attribute_view_gui attribute=$classifysideadlisting.data_map.state}</h4>
                    {attribute_view_gui attribute=$classifysideadlisting.data_map.summary}
                    <div class="right" style="float:right;">
                        {attribute_view_gui attribute=$classifysideadlisting.data_map.image image_class=eventphoto_img}
                        <cite>{attribute_view_gui attribute=$classifysideadlisting.data_map.price}</cite>
                    </div><!--end div.right-->
                </div><!--end div.listing-->
               {/foreach}
            </div><!--end div#listings_wrap-->
</div><!--end div#class_sidebar_right-->			