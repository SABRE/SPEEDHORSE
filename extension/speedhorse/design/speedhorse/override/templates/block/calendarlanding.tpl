{def $valid_nodes = $block.valid_nodes}
<div id="content_calendar"><!--start div#content-->
		<div id="calendar_controls">
            	<div id="controls_top">
                	<div id="left_controls">
                    	<a href="#" id="calendar_control">Calendar</a>
                    </div><!--end div#left_controls-->
                    <form id="right_controls" action="" method="post">
                    	<div>
                        	<span>Filter By</span>
                            <select>
                            	<option>Region</option>
                            </select>
                            <select>
                            	<option>Event Type</option>
                            </select>
                        </div>
                    </form><!--end form#right_controls-->
                </div><!--end div#controls_top-->
                  <div id="controls_bottom" class="carousel">
                	<div id="left_arrow" class="carousel-prev-page"></div>
                    <div class="months_wrap">
                        {if gt(currentdate()|datetime( 'mydate2', '%n' ),7)}
                          <ul id="months" style="left:auto; right:0px;">
                         {else}
                          <ul id="months">
                         {/if}
                           {if currentdate()|datetime( 'mydate', '%F' )|eq('January')}
                            <li><a href="#" id="jan-a" class="active">January</a></li>
                            {else}
                            <li><a href="#" id="jan-a">January</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('February')}
                            <li><a href="#" id="feb-a" class="active">February</a></li>
                            {else}
                            <li><a href="#" id="feb-a">February</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('March')}
                            <li><a href="#" id="mar-a" class="active">March</a></li>
                            {else}
                            <li><a href="#" id="mar-a">March</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('April')}
                            <li><a href="#" id="apr-a" class="active">April</a></li>
                            {else}
                            <li><a href="#" id="apr-a">April</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('May')}
                            <li><a href="#" id="may-a" class="active">May</a></li>
                            {else}
                             <li><a href="#" id="may-a">May</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('June')}
                            <li><a href="#" id="jun-a" class="active">June</a></li>
                            {else}
                            <li><a href="#" id="jun-a">June</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('July')}
                            <li><a href="#" id="jul-a" class="active">July</a></li>
                            {else}
                            <li><a href="#" id="jul-a">July</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('August')}
                            <li><a href="#" id="aug-a" class="active">August</a></li>
                            {else}
                            <li><a href="#" id="aug-a">August</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('September')}
                            <li><a href="#" id="sep-a" class="active">September</a></li>
                            {else}
                            <li><a href="#" id="sep-a">September</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('October')}
                            <li><a href="#" id="oct-a" class="active">October</a></li>
                            {else}
                            <li><a href="#" id="oct-a">October</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('November')}
                            <li><a href="#" id="nov-a" class="active">November</a></li>
                            {else}
                            <li><a href="#" id="nov-a">November</a></li>
                            {/if}
                            
                            {if currentdate()|datetime( 'mydate', '%F' )|eq('December')}
                            <li><a href="#" id="dec-a" class="active">December</a></li>
                            {else}
                            <li><a href="#" id="dec-a">December</a></li>
                            {/if}
                        </ul><!--end ul#months-->
                    </div><!--end div.months_wrap-->
                    <div id="right_arrow" class="carousel-next-page"></div>
                </div><!--end div#controls_bottom-->
            </div><!--end div#calendar-controls-->
            <div id="calendar_items" >
           
               <div id="jan-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('January')}
                  <div class="item">
                  <div class="date">
                        	<span>Jan</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     
                     <div class="info">
                     		
                            	<div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />                                            
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	
                            
                            {attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                        
                    </div><!--end div.item-->   
                   
                    
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="feb-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('February')}
                  <div class="item">
                  <div class="date">
                        	<span>Feb</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                              <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="mar-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('March')}
                  <div class="item">
                  <div class="date">
                        	<span>Mar</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                           <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="apr-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('April')}
                  <div class="item">
                  <div class="date">
                        	<span>Apr</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="may-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('May')}
                  <div class="item">
                  <div class="date">
                        	<span>May</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                                <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="jun-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('June')}
                  <div class="item">
                  <div class="date">
                        	<span>Jun</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                                <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="jul-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('July')}
                  <div class="item">
                  <div class="date">
                        	<span>Jul</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                           <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="aug-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('August')}
                  <div class="item">
                  <div class="date">
                        	<span>Aug</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                   
                                 <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                           <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#"  id="dhtmlgoodies_control" onclick="slidedown_showHide('gp_{$calc.node_id}');return false;">View a map of this location</a></h3>
                          
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                             <div class="dhtmlgoodies_contentBox" id="gp_{$calc.node_id}" style="width:350px;">
								<div class="dhtmlgoodies_content" id="gp_sub_{$calc.node_id}">
                                
                    	{def $name_pattern = $calc.object.content_class.contentobject_name|explode('>')|implode(',')
                          $name_pattern_array = array('enable_comments', 'enable_tipafriend', 'show_children', 'show_children_exclude', 'show_children_pr_page')}
                                {set $name_pattern  = $name_pattern|explode('|')|implode(',')}
                                {set $name_pattern  = $name_pattern|explode('<')|implode(',')}
                                {set $name_pattern  = $name_pattern|explode(',')}
                                {foreach $name_pattern  as $name_pattern_string}
                                    {set $name_pattern_array = $name_pattern_array|append( $name_pattern_string|trim() )}
                                {/foreach}
                            {foreach $calc.object.contentobject_attributes as $attribute}
                                {if eq($attribute.contentclass_attribute_identifier,'location_gmap')}
                                    {if $name_pattern_array|contains($attribute.contentclass_attribute_identifier)|not()}
                                        <div class="attribute-{$attribute.contentclass_attribute_identifier}">
                                            {attribute_view_gui attribute=$attribute}
                                        </div>
                                    {/if}
                                {/if}
                            {/foreach}
                            
                    	</div> 
                     </div>
                     
                        </div><!--end div.info-->
                    </div><!--end div.item-->   
                   
                    {/if}
                {/foreach} 	
                         
                </div><!--end div#jan-a-div-->
                
                <div id="sep-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('September')}
                  <div class="item">
                  <div class="date">
                        	<span>Sep</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                            
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                           <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                           
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
             
                <div id="oct-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('October')}
                  <div class="item">
                  <div class="date">
                        	<span>Oct</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                                <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="nov-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('November')}
                  <div class="item">
                  <div class="date">
                        	<span>Nov</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                                               <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
                
                <div id="dec-a-div" class="items">
                {foreach $valid_nodes as $key => $calc}
                	{if $calc.data_map.from_time.data_int|datetime( 'mydate', '%F' )|eq('December')}
                  <div class="item">
                  <div class="date">
                        	<span>Dec</span>
                            <h3>{$calc.data_map.from_time.data_int|datetime( 'mydate1', '%d' )}</h3>
                      </div><!--end div.date-->   
                     <div class="info">
                     <div style="display:none;" id="data_{$calc.node_id}">
                                    <div class="content-view-full">
                                            
                                            <div class="class-event">
                                            
                                          <div class="attribute-header">
                                            {if $calc.data_map.title.has_content}
                                                <h1>{$calc.data_map.title.content|wash()}</h1>
                                            {else}
                                                <h1>{$calc.name|wash()}</h1>
                                            {/if}
                                            </div>
                                            
                                             <div class="attribute-image">
                                               {attribute_view_gui attribute=$calc.data_map.image align=center image_class=artical_img}
                                          	 </div>
                                            
                                            <div class="attribute-byline">
                                            <p>
                                            {if $calc.object.data_map.category.has_content}
                                            <span class="ezagenda_keyword">
                                            {"Category"|i18n("design/ezwebin/full/event")}:
                                            {attribute_view_gui attribute=$calc.object.data_map.category}
                                            </span>
                                            {/if}
                                            
                                            <span class="ezagenda_date">{$calc.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {if $calc.object.data_map.to_time.has_content}
                                                  - {$calc.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M %H:%i")}
                                            {/if}
                                            </span>
                                            
                                                                               
                                            </p>
                                            </div>
                                        
                                            {if $calc.object.data_map.text.has_content}
                                                <div class="attribute-short"><strong>{attribute_view_gui attribute=$calc.object.data_map.text}</strong></div>
                                            {/if}
                                        
                                        
                                            {* if $calc.object.data_map.url.has_content}
                                                <p style="text-align:center;">
                                                <a href={$calc.object.data_map.url.content|ezurl}>{$calc.object.data_map.url.data_text|wash()}</a>
                                                </p>
                                            {/if *}
                                            
                                            <span>{$calc.object.data_map.address.content}</span><br />
                                            <span>{$calc.object.data_map.address1.content}</span><br />                                            
                                            <span>{$calc.object.data_map.city.content}</span><br />                                            
                                            <span>{$calc.object.data_map.state.content}</span><br />                                            
											<span>{$calc.object.data_map.zip.content}</span><br />
                                          </div>
                                        </div>
                                </div><!-- abc -->
                        	{attribute_view_gui attribute=$calc.data_map.image image_class=artical_img}
                            <h2><a href="#data_{$calc.node_id}" rel="facebox">{attribute_view_gui attribute=$calc.data_map.text}</a></h2>
                            <h3><a href="#data_{$calc.node_id}" rel="facebox">View a map of this location</a></h3>
                            <a href="#data_{$calc.node_id}" rel="facebox" class="readmore">Read More &raquo;</a>
                        </div><!--end div.info-->
                    </div><!--end div.item-->     
                    {/if}
                {/foreach} 	
                </div><!--end div#jan-a-div-->
            </div>
		</div><!--end div#content-->
		