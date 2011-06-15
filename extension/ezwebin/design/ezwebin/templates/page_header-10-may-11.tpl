  <!-- <div id="header-position">
  <div id="header" class="float-break"> -->
   <!-- <div id="usermenu"> -->
      {* include uri='design:page_header_languages.tpl' *}
  
      {* include uri='design:page_header_links.tpl' *}
    <!-- </div> -->

    {* include uri='design:page_header_logo.tpl' *}
    
     {* include uri='design:page_header_searchbox.tpl' *}
    
    <!-- <p class="hide"><a href="#main">{'Skip to main content'|i18n('design/ezwebin/pagelayout')}</a></p> -->
	
  <!-- </div>
  </div> -->
  <div id="header">
		<div id="branding">
			<h1 id="site_title">
				<a href="/">speedhorse - your global connection in the quarter horse racing world</a>
			</h1>
			<div id="utility_menu">
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
				
				<ul>
				
						{if $basket_is_empty|not()}
							<li><a href={"/shop/basket/"|ezurl} title="{$pagedesign.data_map.shopping_basket_label.data_text|wash}">{$pagedesign.data_map.shopping_basket_label.data_text|wash}</a></li>
						 {/if}
				
						{if $current_user.is_logged_in}
							{if $pagedesign.data_map.my_profile_label.has_content}
							<li id="myprofile"><a href={"/user/edit/"|ezurl} title="{$pagedesign.data_map.my_profile_label.data_text|wash}">{$pagedesign.data_map.my_profile_label.data_text|wash}</a></li>
							{/if}
							{if $pagedesign.data_map.logout_label.has_content}
							<li id="logout"><a href={"/user/logout"|ezurl} title="{$pagedesign.data_map.logout_label.data_text|wash}">{$pagedesign.data_map.logout_label.data_text|wash} ( {$current_user.contentobject.name|wash} )</a></li>
							{/if}
						{else}
							
							{if $pagedesign.data_map.login_label.has_content}
							<li id="login"><a href={"/user/login"|ezurl} title="{$pagedesign.data_map.login_label.data_text|wash}">join</a></li>
							{/if}
							
							{if and( $pagedesign.data_map.register_user_label.has_content, ezmodule( 'user/register' ) )}
							<li><a href={"/user/register"|ezurl} title="{$pagedesign.data_map.register_user_label.data_text|wash}">sign in</a></li>
							{/if}
						{/if}
					<li>
					{include uri='extension/ngconnect/design/standard/templates/ngconnect/ngconnect.tpl'}
					</li>
				</ul>
				
			</div>
			<a id="print_edition" href="#"></a>
			
		</div>
		<div id="navigation">
			<ul>
				<!-- <li class="current_page"><a class="current_page" href="index.html">home</a></li> -->
				{include uri=concat('design:menu/', $pagedata.top_menu, '.tpl')}
			</ul>
		</div>
	</div>
	