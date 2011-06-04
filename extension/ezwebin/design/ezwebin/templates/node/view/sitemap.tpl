{def $page_limit=100
     $col_count=2
     $sub_children=0
     $children=fetch('content','list',hash('parent_node_id', $node.node_id,
                                           'limit', $page_limit,
                                           'offset', $view_parameters.offset,
                                           'sort_by', ''))}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-sitemap">

<div class="attribute-header">
    <h1 class="long">{"Site map"|i18n("design/ezwebin/view/sitemap")} {$node.name|wash}</h1>
</div>

<table width="100%" cellspacing="0" cellpadding="4">
<tr>
{foreach $children as $key => $child}
    {if $child.class_identifier|eq( 'breeder' )}
	{elseif $child.class_identifier|eq( 'trainer' )}
	{elseif $child.class_identifier|eq( 'jockey_details' )}
	{elseif $child.class_identifier|eq( 'broodmare_sires_details' )}
    {elseif $child.class_identifier|eq( 'dam_details' )}
	{elseif $child.class_identifier|eq( 'dam_of_sire' )}
	{elseif $child.class_identifier|eq( 'gender_type' )}
	{elseif $child.class_identifier|eq( 'horse_details' )}
	{elseif $child.class_identifier|eq( 'owner' )}
	{elseif $child.class_identifier|eq( 'owner' )}
	{elseif $child.class_identifier|eq( 'sires_details' )}
	{elseif $child.class_identifier|eq( 'sires_of_sires' )}
	{elseif $child.class_identifier|eq( 'statistics' )}			
	{else}
	<td>
	<h2><a href={$child.url_alias|ezurl}>{$child.name}</a></h2>
    {if $child.class_identifier|eq( 'event_calendar' )}
        {set $sub_children=fetch('content','list',hash( 'parent_node_id', $child.node_id, 
                                                        'limit', $page_limit,
                                                        'sort_by', array( 'attribute', false(), 'event/from_time' ) ) )}

 	 {else}
	
        {set $sub_children=fetch('content','list',hash( 'parent_node_id', $child.node_id,
                                                        'limit', $page_limit,
                                                        'sort_by', $child.sort_array))}
														
    {/if}
    <ul>
    {foreach $sub_children as $sub_child}
    <li><a href={$sub_child.url_alias|ezurl}>{$sub_child.name}</a></li>
    {/foreach}
    </ul>
    </td>
	
	
    {if ne( $key|mod($col_count), 0 )}
</tr>
<tr>
    {/if}{/if}
{/foreach}
</tr>
</table>

</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>