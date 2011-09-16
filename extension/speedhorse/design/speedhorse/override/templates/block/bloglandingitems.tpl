{def $col=1}
{def $col2=0}
{def $valid_nodes = $block.valid_nodes}
{def $children = array()
 $children_count = ''} 
 
 {* RELATED IMAGES *}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $valarticle=""}


 
<div id="content"><!--start div#content-->
    <div class="category_posts">
    {foreach $valid_nodes as $key => $bloglandingcategory}	
		<h3 class="category_title">
       	<a href="#">{attribute_view_gui attribute=$bloglandingcategory.data_map.name}</a>
        <span><a href="#header">Back to top</a></span>
       	</h3><!--end h3.category_title-->
  		 {if $bloglandingcategory.data_map.show_children}
	          {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $bloglandingcategory.node_id) )}
         {/if}   
              {if $children_count}
				{foreach fetch_alias( 'children', hash( 'parent_node_id', $bloglandingcategory.node_id) ) as $child }
                	
                    {set $my_node1=fetch( 'content', 'node', hash( 'node_id', $child.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}	
                                	{if eq($col2,0)}
                                        {if eq($value1.contentclass_id,'33')}
                                        {set $rvalue=$value1.id}
                                        {/if}
                                    {/if}
                                    {set col2=$col2|inc}
                                {/foreach}
                                {set col2=0}
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))} 
                
					{if gt($col,1)}
					{set $col=1}
                        <div class="post" style="margin-right:20px;">
                        {attribute_view_gui attribute=$valarticle.data_map.image image_class=blog_img href=$child.url_alias|ezurl()}
                        <div class="post_right">
                            <h4><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.name}</a></h4>
                            <p>{attribute_view_gui attribute=$child.data_map.description}</p>
                        </div><!--end div.post_right-->
                        </div><!--end div.post-->
					{else}
                        <div class="post">
                        {attribute_view_gui attribute=$valarticle.data_map.image image_class=blog_img href=$child.url_alias|ezurl()}
                        <div class="post_right">
                            <h4><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.name}</a></h4>
                            <p>{attribute_view_gui attribute=$child.data_map.description}</p>
                        </div><!--end div.post_right-->
	                   </div><!--end div.post-->
					{set col=$col|inc}
					{/if}	
			  {/foreach}
			{/if}	            
	{/foreach}	
   </div><!--end div.category_posts-->
</div><!--end div#content-->