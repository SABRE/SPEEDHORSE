<div style="width:940px;">

{* RELATED IMAGES *}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $valarticle=""}
{set $my_node1=fetch( 'content', 'node', hash( 'node_id', $node.node_id ) )}
                
                                {set $related_objects1=fetch( 'content', 'related_objects',hash( 'object_id', $my_node1.object.id ) )}
                                {set $rvalue=''}
                                {foreach $related_objects1 as $key1 => $value1}
                                 {if eq($value1.contentclass_id,'33')}
                                    {set $rvalue=$value1.id}
                                 {/if}
                                {/foreach}
                                {set $valarticle=fetch( 'content', 'object', hash( 'object_id', $rvalue ))} 



{def $my_node=fetch( 'content', 'node', hash( 'node_id', $node.node_id ) )}

<div id="the_post1"><h2>{attribute_view_gui attribute=$my_node.object.data_map.name}</h2></div>   
<div class="post-info1"><h3><span style="font-weight:bold;">{attribute_view_gui attribute=$my_node.object.data_map.description}</span></h3></div>

{attribute_view_gui attribute=$valarticle.data_map.image image_class=blogfulcat_img}
<hr class="myhr" />  

<div style="float:left; width:600px;">
<div id="content"><!--start div#content-->
	{def $page_limit = 10
     $blogs_count = 0
     $uniq_id = 0
     $uniq_post = array()}

{if $view_parameters.tag}
    {set $blogs_count = fetch( 'content', 'keyword_count', hash( 'alphabet', rawurldecode( $view_parameters.tag ),
                                                     'classid', 'blog_post',
                                                     'parent_node_id', $node.node_id ) )}
    {if $blogs_count}
        {foreach fetch( 'content', 'keyword', hash( 'alphabet', rawurldecode( $view_parameters.tag ),
                                                    'classid', 'blog_post',
                                                    'parent_node_id', $node.node_id,
                                                    'offset', $view_parameters.offset,
                                                    'sort_by', array( 'attribute', false(), 'blog_post/publication_date' ),
                                                    'limit', $page_limit ) ) as $blog}
            {set $uniq_id = $blog.link_object.node_id}
            {if $uniq_post|contains( $uniq_id )|not}
                {node_view_gui view=line content_node=$blog.link_object}
                {set $uniq_post = $uniq_post|append( $uniq_id )}
            {/if}
        {/foreach}
    {/if}
{else}
    {if and( $view_parameters.month, $view_parameters.year )}
        {def $start_date = maketime( 0,0,0, $view_parameters.month, cond( ne( $view_parameters.day , ''), $view_parameters.day, '01' ), $view_parameters.year)
             $end_date = maketime( 23, 59, 59, $view_parameters.month, cond( ne( $view_parameters.day , ''), $view_parameters.day, makedate( $view_parameters.month, '01', $view_parameters.year)|datetime( 'custom', '%t' ) ), $view_parameters.year)}

        {set $blogs_count = fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
                                                                  'attribute_filter', array( and,
                                                                         array( 'blog_post/publication_date', '>=', $start_date ),
                                                                         array( 'blog_post/publication_date', '<=', $end_date) ) ) )}
        {if $blogs_count}
            {foreach fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                     'offset', $view_parameters.offset,
                                                     'attribute_filter', array( and,
                                                                                 array( 'blog_post/publication_date', '>=', $start_date ),
                                                                                 array( 'blog_post/publication_date', '<=', $end_date ) ),
                                                     'sort_by', array( 'attribute', false(), 'blog_post/publication_date' ),
                                                     'limit', $page_limit ) ) as $blog}
                {node_view_gui view=line content_node=$blog}
            {/foreach}
        {/if}
    {else}
        {set $blogs_count = fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id ) )}
        {if $blogs_count}
            {foreach fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                     'offset', $view_parameters.offset,
                                                     'sort_by', array( 'attribute', false(), 'blog_post/publication_date' ),
                                                     'limit', $page_limit ) ) as $blog}
                {node_view_gui view=line content_node=$blog}
            {/foreach}
        {/if}
    {/if}
{/if}

            {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$blogs_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}

            	
		</div><!--end div#content-->
</div>
<div style="float:left; width:340px;">
{attribute_view_gui attribute=$node.object.data_map.layout}
  </div> 
</div>           