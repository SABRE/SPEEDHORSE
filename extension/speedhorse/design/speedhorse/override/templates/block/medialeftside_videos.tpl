{def $col=1}
{def $valid_nodes = $block.valid_nodes}

 <div id="media_wrap">
            	<div id="media_options">
                	<div id="options_controls">
                    	<form action="" method="post">
                            <label>Media Type
                                <select>
                                    <option value="Video">Video</option>
                                </select>
                            </label>
                            <label>Date
                                <select>
                                    <option value="Select">Select</option>
                                </select>
                            </label>
                        </form>
                        <form action="" method="post" id="search">
                        	<label>Search Media
                            	<input type="text" name="search" id="search_bar" />
                            </label>
                                <input type="submit" id="submit" value="search" />
                        </form>
                    </div><!--end div#options_controls-->
                	<div id="tags">
                		<h3>Tags</h3>
                        <a href="#">Races</a>
                        <a href="#">Sales</a>
                        <a href="#">News</a>
                        <a href="#">Events</a>
                        <a href="#">Derby</a>
                    </div><!--end div#tags-->
                </div><!--end div#media_options-->
                <br /><br />

                 {def $defplayer = ezini( 'BOTRSettings', 'DefaultPlayer', 'botr.ini' )}
	
                {if is_set($player)|not}
                    {def $player = $defplayer}
                {/if}
                
                {if $player|eq('')}
                    {set $player = $defplayer}
                {/if}
                {def $player_r = botr_player($player)}
                {def $vid_id = ""}
                
               
                <div id="media">
                 {foreach $valid_nodes as $key => $myvideo }
               
                <div class="media_item">
                 <a href="#" class="basic" id="aa_basic-modal-content_{$myvideo.node_id}">{attribute_view_gui attribute=$myvideo.data_map.video_image image_class=mediapreview}</a>
                 <div id="my_mediaid">{attribute_view_gui attribute=$myvideo.data_map.name}</div>
                 </div>
                 
                 <div id="basic-modal-content_{$myvideo.node_id}" class="basic-modal-content" style="width:610px;height:400px;">
                     <div class="media_item">
                     
                     	{set $vid_id = $myvideo.data_map.botr_video.content}
                        
                        <div id="botr_{$vid_id}_{$player}_div" style='width: {sum($player_r['width'], 15)}px; height: {sum($player_r['height'], 15)}px;' class="botrplayer"></div>
                      
<script type="text/javascript" src="/extension/multimedia/design/standard/javascript/bits_modified.php?keys={$vid_id}-{$player}"></script>
						{attribute_view_gui attribute=$myvideo.data_map.name}
                    	{attribute_view_gui attribute=$myvideo.data_map.description}
                     </div>
                        <div style='display:none'>
                            <img src='/extension/speedhorse/design/speedhorse/images/x.png' alt='' />
                        </div>
                  </div>  
                {/foreach}
                 </div><!--end div#media--> 
            </div><!--end div#media_wrap-->
