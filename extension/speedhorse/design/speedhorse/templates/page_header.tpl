<!--  <div id="header-position">
  <div id="header" class="float-break">
    <div id="usermenu">
      {include uri='design:page_header_languages.tpl'}
    </div>

    {include uri='design:page_header_logo.tpl'}
    
    {include uri='design:page_header_searchbox.tpl'}

    <div class="corner-box">
    <div class="corner-tl"><div class="corner-tr">
    <div class="corner-content">
      {include uri='design:page_header_links.tpl'}
    </div>
    </div></div>
    </div>
      
    <p class="hide"><a href="#main">{'Skip to main content'|i18n('design/ezflow/pagelayout')}</a></p>
  </div>
  </div> -->
  
  <div id="header"><!--start div#header-->
		<div id="branding"><!--start div#branding-->
			<h1 id="site_title"><!--start h1#site_title-->
				<a href="/">speedhorse - your global connection in the quarter horse racing world</a>
			</h1><!--end h1#site_title-->
			<div id="utility_menu"><!--start div#utility_menu-->
				<!--
				Presumably the client has a search widget available through his CMS to use in this space.
				I include a form for looks and as a possible alternative.
				-->
				<form action={"/content/search"|ezurl}>
					
					{if $pagedata.is_edit}
						<input id="searchtext" name="SearchText" type="text" value="" size="12" disabled="disabled" />
						<input id="searchbutton" class="button-disabled" type="submit"  disabled="disabled" />
						{else}
						<input id="searchtext" name="SearchText" type="text" value="" placeholder="Search Site" />
						<input id="searchbutton"  type="submit"  />
							{if eq( $ui_context, 'browse' )}
							 <input name="Mode" type="hidden" value="browse" />
							{/if}
					{/if}
				</form>
				<ul><!--start ul-->
					{if $basket_is_empty|not()}
							<li><a href={"/shop/basket/"|ezurl} title="{$pagedesign.data_map.shopping_basket_label.data_text|wash}">{$pagedesign.data_map.shopping_basket_label.data_text|wash}</a></li>
						 {/if}
				
						{if $current_user.is_logged_in}
							{if $pagedesign.data_map.my_profile_label.has_content}
							<li id="myprofile"><a href={"/user/edit/"|ezurl} title="{$pagedesign.data_map.my_profile_label.data_text|wash}">{$pagedesign.data_map.my_profile_label.data_text|wash}</a></li>
							{/if}
							{if $pagedesign.data_map.logout_label.has_content}
							<li id="logout">&nbsp;&nbsp;<a href={"/user/logout"|ezurl} title="{$pagedesign.data_map.logout_label.data_text|wash}">{$pagedesign.data_map.logout_label.data_text|wash}</a></li>
							{/if}
						{else}
							
							{if $pagedesign.data_map.login_label.has_content}
							<li id="login"><a href={"/user/login"|ezurl} title="{$pagedesign.data_map.login_label.data_text|wash}">sign in</a></li>
							{/if}
							
							{if and( $pagedesign.data_map.register_user_label.has_content, ezmodule( 'user/register' ) )}
							<li><a href={"/user/register"|ezurl} title="{$pagedesign.data_map.register_user_label.data_text|wash}">join</a></li>
							{/if}
							<li>
							{include uri='extension/ngconnect/design/standard/templates/ngconnect/ngconnect.tpl'}
							</li>
						{/if}
					
				</ul><!--end ul-->
			</div><!--end div#utility_menu-->
			<a id="print_edition" href="#"></a>
		</div><!--end div#branding-->
		
		{def $root_node=fetch( 'content', 'node', hash( 'node_id', $pagedata.root_node) )
         $top_menu_items=fetch( 'content', 'list', hash( 'parent_node_id', $root_node.node_id,
                                                          'sort_by', $root_node.sort_array,
                                                          'class_filter_type', 'exclude',
                                                          'class_filter_array', array( 'gallery', 'feedback_form', 'folder','forum','classified_folder','calander','blog' ) ) )
         $top_menu_items_count = $top_menu_items|count()}
		 
		  {def $current_node_in_path = first_set($pagedata.path_array[1].node_id, 0  )}
			
			
			
				{def $my_node=fetch( 'content', 'node', hash( 'node_id', $current_node_in_path ) )}
			
			
			
			{def $children = array()
                 $children_count = ''}

		 {if $top_menu_items_count}
			 <div id="navigation"><!--start div#navigation-->
			
				
			 		 
					
					{if $my_node.data_map.container_id.content}
					
						<input type="hidden" id="mymodeid" name="mymodeid" value="node_id_{$my_node.data_map.container_id.content}" />
					{else}
					<input type="hidden" id="mymodeid" name="mymodeid" value="node_id_{$current_node_in_path}" />
					{/if}
					
				 <ul class="main"><!--start ul-->
				   {foreach $top_menu_items as $key => $item}
				   {if eq($key,0)}
						<li  class="main" id="node_id_{$item.node_id}"><a href="/" class="main" id="a_node_id_{$item.node_id}">{$item.name|wash()}</a>
					{else}
						<li  class="main" id="node_id_{$item.node_id}"><a href={$item.url_alias|ezurl} class="main" id="a_node_id_{$item.node_id}">{$item.name|wash()}</a>
					{/if}
						
				 	
					</li>							  
					{/foreach}
				</ul>	
			</div><!--end div#navigation-->
			
			 {foreach $top_menu_items as $key => $item}
					{set $children_count=fetch_alias( 'children_count', hash( 'parent_node_id', $item.node_id) )}
					{if $children_count}
						
	                        <ul class="subnav" id="subnav_{$item.node_id}" style="display:none;">
                   			{foreach fetch( 'content', 'list', hash( 'parent_node_id', $item.node_id,
                                                          'sort_by', $item.sort_array,
                                                          'class_filter_type', 'exclude',
                                                          'class_filter_array', array( 'gallery', 'feedback_form', 'folder','forum','classified_folder','calander','blog' ) ) ) as $child}	
											  
							<li><a href={$child.url_alias|ezurl} >{$child.name|wash()}</a></li>
							{/foreach}
							</ul>
					  					  
					{/if}
			{/foreach}
		{/if}
		
				
		
	</div><!--end div#header-->
  
  <!-- Content area: START -->
  <div id="page-content-position">
  <div id="page-content">