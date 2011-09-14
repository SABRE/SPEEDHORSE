{* Blog post - Line view *}
{def $related_objects1=""}
{def $my_node1=""}
{def $rvalue=''}
{def $chk=0}
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
                                
    <div class="category_posts">
         <div class="blogpost" >
            {attribute_view_gui attribute=$valarticle.data_map.image image_class=blog_img href=$node.url_alias|ezurl()}
            <div class="blogpost_right">
         	   <h4><a href={$node.url_alias|ezurl} title="{$node.data_map.title.content|wash}">{$node.data_map.title.content|wash}</a></h4>
               <p>{attribute_view_gui attribute=$node.data_map.intro}</p>
                   <div style="position:relative;bottom:0;">
                                    <h6 class="post_date">Posted on: {$node.data_map.publication_date.data_int|datetime( 'mydate5', '%l')}, {$node.data_map.publication_date.data_int|datetime( 'mydate', '%F')} {$node.data_map.publication_date.data_int|datetime( 'mydate1', '%d' )}, {$node.data_map.publication_date.data_int|datetime( 'mydate4', '%Y' )}</h6>
                                    <h6>{$node.object.owner.name}</h6>
                    </div>
               <em class="blogem"><a href={$node.url_alias|ezurl()}>Read More &raquo;</a></em>
            </div><!--end div.post_right-->
         </div><!--end div.post-->
   </div><!--end div.category_posts-->



