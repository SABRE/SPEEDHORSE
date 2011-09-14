{def $valid_nodes = $block.valid_nodes}
<div id="sidebar"><!--start div#sidebar-->
	{foreach $valid_nodes as $key => $calendarphotoevent}	
	        <div id="picture_week" style="float:left;">
                <h3>{attribute_view_gui attribute=$calendarphotoevent.data_map.title}</h3>
                <div style="margin:10px 0 0 0;">
                	<div style="float:left;margin:0 10px 10px 0;">{attribute_view_gui attribute=$calendarphotoevent.data_map.image image_class=eventphoto_img}</div>
                    <span>{attribute_view_gui attribute=$calendarphotoevent.data_map.from_time} &nbsp;&nbsp;&nbsp; {attribute_view_gui attribute=$calendarphotoevent.data_map.city}, {attribute_view_gui attribute=$calendarphotoevent.data_map.state}</span>
                    <p>{attribute_view_gui attribute=$calendarphotoevent.data_map.text}</p>
                </div>
                <span class="readmore"><a href={$calendarphotoevent.url_alias|ezurl()} class="readmore">Read More &raquo;</a></span>
            </div><!--end div#picture_week-->
      {/foreach}      
</div><!--end div#sidebar -->