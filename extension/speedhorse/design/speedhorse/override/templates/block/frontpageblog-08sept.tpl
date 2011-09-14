{def $col=1}
{def $valid_nodes = $block.valid_nodes}

<div id="content_blog" style="z-index:500">
<div id="blogs_preview_wrap">
				<div id="blogs_preview">
					<h2 class="section_title">{$block.name}</h2>
                     {def $bid=0}
                        {foreach $valid_nodes as $key => $classifycat1 max 1}
                        {set $bid=$classifycat1.parent_node_id}
                        {/foreach}
                
                    {def $my_node=fetch( 'content', 'node', hash( 'node_id', $bid ) )}
                    {let related_objects=fetch( 'content', 'related_objects',
                        hash( 'object_id', $my_node.object.id  ) )}
                    {def $myobjid=0}
                    {section loop=$related_objects var=related_object}
                        {set $myobjid=$related_object.id}
                    {/section}	
    
                     {def $valblog=fetch( 'content', 'object', hash( 'object_id', $myobjid ))}
                        <a id="blogs_preview_ad_link" href={attribute_view_gui attribute=$valblog.data_map.company_website} target="_blank">{attribute_view_gui attribute=$valblog.data_map.sponsores_image }</a>
                  
					<div>
					{foreach $valid_nodes as $key => $blog}
					<div class="post">
					<img class="post_thumbnail" src="../images/blog_author.jpg" alt="post_thumbnail">
							<h3><a href={$blog.url_alias|ezurl()}>{$blog.name}</a></h3>
							<h5><a href="#">-{$blog.object.current.creator.name|wash()}</a></h5>
							<div class="post_excerpt">
							<div style="float: left;margin-right: 10px;">{attribute_view_gui attribute=$blog.data_map.image image_class='blog_img' href=$blog.url_alias|ezurl()}</div>
								<h6><a href={$blog.url_alias|ezurl()}>{attribute_view_gui attribute=$blog.data_map.headline}</a></h6>
								<a href={$blog.url_alias|ezurl()}>{attribute_view_gui attribute=$blog.data_map.intro}</a>
							</div>
							<div class="more_link_wrap"><a href={$blog.url_alias|ezurl()} class="more_link">read more &#xBB;</a></div>
							<div class="post_meta">
								<h6 class="post_date"><a href={$blog.url_alias|ezurl()}>posted on: {$blog.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$blog.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$blog.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$blog.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</a></h6>
								<h4><a href={$blog.url_alias|ezurl()}>21 comments</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href={$blog.url_alias|ezurl()}>219 views</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href={$blog.url_alias|ezurl()}>rating</a> &#x2605;&#x2605;&#x2605;&#x2605;<span class="unattained_star">&#x2605;</span></h4>
							</div>
						</div>
						{/foreach}	
					</div>
				</div><!-- #blogs_preview -->
			</div><!-- #blogs_preview_wrap -->
		</div> <!-- content_blog -->	
