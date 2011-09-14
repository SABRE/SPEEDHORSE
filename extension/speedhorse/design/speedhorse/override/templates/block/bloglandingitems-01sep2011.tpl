{def $col=1}
{def $valid_nodes = $block.valid_nodes}
            <div class="category_posts">
            {foreach $valid_nodes as $key => $bloglandingitem}	
			{if gt($col,1)}
				{set $col=1}
				<div class="post">
                	 <div style="width:80px; height:80px;">{attribute_view_gui attribute=$bloglandingitem.data_map.image image_class='small_blog'}</div>
                    <div class="post_right">
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.title}</a></h4>
                        <cite>-{$bloglandingitem.object.current.creator.name|wash()}</cite>
                        <h5>{attribute_view_gui attribute=$bloglandingitem.data_map.headline}</h5>
						<p>{attribute_view_gui attribute=$bloglandingitem.data_map.intro}</p>
                        <em><a href={$bloglandingitem.url_alias|ezurl()}>Read More &raquo;</a></em>
                    </div><!--end div.post_right-->
			
                   <div style="position:relative;bottom: 0;">
								<h6 class="post_date">posted on: {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</h6>
								<h4>21 comments&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;219 views&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;rating &#x2605;&#x2605;&#x2605;&#x2605;<span class="unattained_star">&#x2605;</span></h4>
							</div>
                </div><!--end div.post-->
				
			{else}
			<div class="post" style="margin-right:20px;">
                	 {attribute_view_gui attribute=$bloglandingitem.data_map.image image_class='small_blog'}
                    <div class="post_right">
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.title}</a></h4>
                        <cite>-{$bloglandingitem.object.current.creator.name|wash()}</cite>
                        <h5>{attribute_view_gui attribute=$bloglandingitem.data_map.headline}</h5>
                         <p>{attribute_view_gui attribute=$bloglandingitem.data_map.intro}</p>
                        <em><a href={$bloglandingitem.url_alias|ezurl()}>Read More &raquo;</a></em>
                    </div><!--end div.post_right-->
                   
                   <div style="position:relative;bottom: 0;">
								<h6 class="post_date">posted on: {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</h6>
								<h4>21 comments&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;219 views&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;rating &#x2605;&#x2605;&#x2605;&#x2605;<span class="unattained_star">&#x2605;</span></h4>
							</div>
               
                </div><!--end div.post-->
			
			{set col=$col|inc}
			{/if}	
			  {/foreach}	
            </div><!--end div.category_posts-->