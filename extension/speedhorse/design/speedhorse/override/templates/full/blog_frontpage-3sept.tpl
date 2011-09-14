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

		<div id="sidebar"><!--start div#sidebar-->
			{def $children = array()
                 $children_count = ''}
                 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '266') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '266')  ) as $child max 1}
                           				{attribute_view_gui attribute=$child.data_map.ad_image href=$child.url_alias|ezurl()}
                    			{/foreach}
       				{/if}        
		<div style="height:35px;"></div>
            
            <h2 class="section_title" style="float:left;">Tags</h2>
            
            <div id="blog_tags" style="float:left;">
            {foreach $node.data_map.tags.content.keywords as $keyword}
                                             <a href={concat( $node.parent.url_alias, "/(id)/", $node.parent.node_id, "/(tag)/", $keyword|rawurlencode )|ezurl} title="{$keyword}">{$keyword}</a>
                                             {delimiter}
                                               ,
                                             {/delimiter}
                                         {/foreach}
            	
            </div><!--end div#blog_tags-->
            
            <h2 class="section_title" style="float:left;">Blog Archives</h2>
            	{include uri='design:parts/blog/extra_info.tpl' used_node=$node.parent}
            
        			
            
			<a id="sidebar_site_link_1" href="#" style="float:left;"></a>
			<a id="sidebar_site_link_2" href="#" style="float:left;"></a>

			<h2 class="section_title" style="float:left;">special focus title</h2>
			<ul class="speedhorse_list" style="float:left; width:100%;">
			{def $children = array()
                 $children_count = ''}
                 {set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', '288') )}
       				 {if $children_count}
                   			    {foreach fetch_alias( 'children', hash( 'parent_node_id', '288')  ) as $child max 7}
                           				<li><a href={$child.url_alias|ezurl()}>{attribute_view_gui attribute=$child.data_map.title}</a></li>
                    			{/foreach}
       				{/if} 
            </ul>
 			</div><!--end div#sidebar -->