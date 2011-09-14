{def $valid_nodes = $block.valid_nodes}
<div id="sidebar"><!--start div#sidebar-->
        	{foreach $valid_nodes as $key => $mediaphoto}           
            <div id="picture_week">
            	<h2>{$block.name}</h2>
                {attribute_view_gui attribute=$mediaphoto.data_map.image image_class=mediaphoto_img href=$mediaphoto.url_alias|ezurl()}
                <h3>{attribute_view_gui attribute=$mediaphoto.data_map.name}</h3>
                <span>{attribute_view_gui attribute=$mediaphoto.data_map.caption}</span>
            </div><!--end div#video_week-->

</div><!--end div#sidebar -->