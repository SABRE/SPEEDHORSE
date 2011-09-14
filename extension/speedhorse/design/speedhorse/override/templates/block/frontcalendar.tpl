{def $valid_nodes = $block.valid_nodes}
<div id="class_sidebar_right">
	<div id="sidebar">
<div id="calendar">
				<h2 class="section_title">{$block.name}</h2>
				<ul class="speedhorse_list">
				{foreach $valid_nodes as $key => $event max 4}
				                     <div style="display:none;" id="data_{$event.node_id}">
                                          <div class="content-view-full">
                                            <div class="class-event">
                                          	<div class="attribute-header">
                                            {if $event.data_map.title.has_content}
                                                <h1>{$event.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$event.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$event.data_map.image align=center image_class=imagelarge}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $event.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$event.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$event.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $event.object.data_map.to_time.has_content}
                                                  - {$event.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $event.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$event.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $event.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$event.object.data_map.url.content|ezurl}>{$event.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$event.object.data_map.address.content}</span><br />
                                            <span>{$event.object.data_map.address1.content}</span><br />                                            
                                            <span>{$event.object.data_map.city.content}</span><br />                                            
                                            <span>{$event.object.data_map.state.content}</span><br />                                            
											<span>{$event.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
					<li><a href="#data_{$event.node_id}" rel="facebox">{attribute_view_gui attribute=$event.data_map.short_title}</a></li>
				{/foreach}	
				</ul>
			</div><!--end div#calendar-->
	</div><!--end div#sidebar-->
</div><!--end div#class_sidebar_right-->					
