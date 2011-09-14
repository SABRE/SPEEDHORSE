<div class="zone-layout-{$zone_layout|downcase()}" style="margin-left:-110px;">
<div class="float-break content-columns">
<div style="width:940px;">
<div class="myleftcol-position">
<div class="myleftcol">
<!-- ZONE CONTENT: START -->
<div>
<div><div><div>
<div>
{if and( is_set( $zones[0].blocks ), $zones[0].blocks|count() )}
	{foreach $zones[0].blocks as $block}
		{if or( $block.valid_nodes|count(),
		and( is_set( $block.custom_attributes), $block.custom_attributes|count() ),
		and( eq( ezini( $block.type, 'ManualAddingOfItems', 'block.ini' ), 'disabled' ), ezini_hasvariable( $block.type, 'FetchClass', 'block.ini' )|not ) )}
			<div id="address-{$block.zone_id}-{$block.id}">
			{block_view_gui block=$block}
			</div>
		{else}
			{skip}
		{/if}
	
	{/foreach}
{/if}
</div>
</div></div></div>
<div><div><div></div></div></div>
</div>
<!-- ZONE CONTENT: END -->
</div>
</div>
<div class="maincentercol-position">
<div class="maincentercol">
<!-- ZONE CONTENT: START -->
<div>
<div><div><div>
<div>
	{if and( is_set( $zones[1].blocks ), $zones[1].blocks|count() )}
		{foreach $zones[1].blocks as $block}
			{if or( $block.valid_nodes|count(),
			and( is_set( $block.custom_attributes), $block.custom_attributes|count() ),
			and( eq( ezini( $block.type, 'ManualAddingOfItems', 'block.ini' ), 'disabled' ), ezini_hasvariable( $block.type, 'FetchClass', 'block.ini' )|not ) )}
				<div id="address-{$block.zone_id}-{$block.id}">
				{block_view_gui block=$block}
				</div>
			{else}
				{skip}
			{/if}
		
		{/foreach}
	{/if}
</div>
</div></div></div>
<div><div><div></div></div></div>
</div>
<!-- ZONE CONTENT: END -->
<!-- COLUMNS TWO: START -->
<!-- COLUMNS TWO: END -->
</div>
</div>

<div class="myrightcol-position">
<div class="myrightcol">
<!-- ZONE CONTENT: START -->
<div>
<div><div><div>
<div>
	{if and( is_set( $zones[2].blocks ), $zones[2].blocks|count() )}
		{foreach $zones[2].blocks as $block}
			{if or( $block.valid_nodes|count(),
			and( is_set( $block.custom_attributes), $block.custom_attributes|count() ),
			and( eq( ezini( $block.type, 'ManualAddingOfItems', 'block.ini' ), 'disabled' ), ezini_hasvariable( $block.type, 'FetchClass', 'block.ini' )|not ) )}
				<div id="address-{$block.zone_id}-{$block.id}">
				{block_view_gui block=$block}
				</div>
			{else}
				{skip}
			{/if}
		
		{/foreach}
	{/if}
</div>
</div></div></div>
<div><div><div></div></div></div>
</div>
<!-- ZONE CONTENT: END -->
</div>
</div>
</div>
</div>
</div>