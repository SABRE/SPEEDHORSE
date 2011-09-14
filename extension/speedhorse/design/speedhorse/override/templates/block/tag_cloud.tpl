<div class="block-type-tagcloud block-view-{$block.view}">

<!--<div class="attribute-header"><h2>{$block.name|wash()}</h2></div>-->

<h2 class="section_title" style="float:left;">Tags</h2>
 <div id="blog_tags" style="float:left;">
 	{eztagcloud( hash( 'parent_node_id', $block.custom_attributes.subtree_node_id ))}
 </div><!--end div#blog_tags-->

</div>