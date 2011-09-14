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
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.title}</a></h4>
                        <cite>-{$bloglandingitem.object.current.creator.name|wash()}</cite>
                        <h5>{attribute_view_gui attribute=$bloglandingitem.data_map.headline}</h5></div>
                        <p>{attribute_view_gui attribute=$bloglandingitem.data_map.intro}</p>
                         <div class="post_right"><em><a href={$bloglandingitem.url_alias|ezurl()}>Read More &raquo;</a></em>
                    </div><!--end div.post_right-->
                    <div class="post_foot">
                    	<span class="posted">posted on: {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</span>
                       <span class="comments">21 Comments <cite>|</cite></span>
                        <span class="views">219 Views <cite>|</cite></span>
                        <span class="rating">Rating <img src="/images/rating.jpg" alt="Rating" /></span>
                    </div><!--end div.post_foot-->
                </div><!--end div.post-->
				{else}
                <div class="post">
                	{attribute_view_gui attribute=$bloglandingitem.data_map.image image_class='small_blog' href=$bloglandingitem.url_alias|ezurl()}
                    <div class="post_right">
                    	<h4><a href={$bloglandingitem.url_alias|ezurl()}>{attribute_view_gui attribute=$bloglandingitem.data_map.title}</a></h4>
                        <cite>-{$bloglandingitem.object.current.creator.name|wash()}</cite>
                        <h5>{attribute_view_gui attribute=$bloglandingitem.data_map.headline}</h5></div>
                        <p>{attribute_view_gui attribute=$bloglandingitem.data_map.intro}</p>
                        <div class="post_right"> <em><a href={$bloglandingitem.url_alias|ezurl()}>Read More &raquo;</a></em>
                    </div><!--end div.post_right-->
                    <div class="post_foot">
                    	<span class="posted">posted on: {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$bloglandingitem.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</span>
                       <span class="comments">21 Comments <cite>|</cite></span>
                        <span class="views">219 Views <cite>|</cite></span>
                        <span class="rating">Rating <img src="/images/rating.jpg" alt="Rating" /></span>
                    </div><!--end div.post_foot-->
                </div><!--end div.post-->
				{set col=$col|inc}
			{/if}	

			{/foreach}

            </div><!--end div.category_posts-->

		</div><!--end div#content-->