{def $vid_id = $attribute.content}
{def $defplayer = ezini( 'BOTRSettings', 'DefaultPlayer', 'botr.ini' )}
	
{if is_set($player)|not}
	{def $player = $defplayer}
{/if}

{if $player|eq('')}
	{set $player = $defplayer}
{/if}

{def $player_r = botr_player($player)}

<div id="botr_{$vid_id}_{$player}_div" style='width: {sum($player_r['width'], 15)}px; height: {sum($player_r['height'], 15)}px' class="botrplayer"></div>

<script type="text/javascript" src="/extension/multimedia/design/standard/javascript/ezbotr_object.js"></script>
<script type="text/javascript" src="/extension/multimedia/design/standard/javascript/bits_modified.php?keys={$vid_id}-{$player}"></script>