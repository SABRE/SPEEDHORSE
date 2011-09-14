{def $valid_nodes = $block.valid_nodes}

<div id="mediamain"  style="width:550px;z-index:-100; display:none;">
   {def $defplayer = ezini( 'BOTRSettings', 'DefaultPlayer', 'botr.ini' )}
 
                {if is_set($player)|not}
                    {def $player = $defplayer}
                {/if}
                
                {if $player|eq('')}
                    {set $player = $defplayer}
                {/if}
                {def $player_r = botr_player($player)}
                {def $vid_id = ""}
                
                            
                   {foreach $valid_nodes as $key => $bvideo}	
               
                     <div id="media_item_{$bvideo.node_id}" class="media_item_preview" style="display:none;">
                     
                     	{set $vid_id = $bvideo.data_map.botr_video.content}
                        
                        <div id="botr_{$vid_id}_{$player}_div" style='width: {sum($player_r['width'], 15)}px; height: {sum($player_r['height'], 15)}px;margin:10px;30px;10px;30px;' class="botrplayer" align="center"></div>
                      
<script type="text/javascript" src="/extension/multimedia/design/standard/javascript/bits_modified.php?keys={$vid_id}-{$player}"></script>
						
           
                     </div>
                        
  
                {/foreach}
				
                 </div><!--end div#media--> 