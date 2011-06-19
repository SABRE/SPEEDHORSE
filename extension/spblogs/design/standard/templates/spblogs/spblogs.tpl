<div style="width:940px;float:left;">
	<div style="width:640px;float:left;">
	
		<div id="content"><!--start div#content-->
        	
            <div id="featured_post_wrap" style="margin-bottom:20px;">
                {$myoutput0}<!--end div.post_content-->
            </div><!--end div#featured_post_wrap-->
            
			<div id="blog_directory" style="border-bottom:none;margin-bottom:0;">
            	<h4>Blog Directory:</h4>
                {$myoutput}
            </div><!--end div#blog_directory-->
            
            <h3 class="category_title">
            	<a href="#">{$optionArray[0][name]}</a>
                <span><a href="#header">Back to top</a></span>
            </h3><!--end h3.category_title-->
            
            <div class="category_posts">
            	{$myoutput2}
            </div><!--end div.category_posts-->
            
            <h3 class="category_title">
            	<a href="#">{$optionArray[1][name]}</a>
                <span><a href="#header">Back to top</a></span>
            </h3><!--end h3.category_title-->
            
            <div class="category_posts">

            	{$myoutput3}
            </div><!--end div.category_posts-->
            
            <h3 class="category_title">
            	<a href="#">{$optionArray[2][name]}</a>
                <span><a href="#header">Back to top</a></span>
            </h3><!--end h3.category_title-->
            
            <div class="category_posts">
            	{$myoutput4}
            </div><!--end div.category_posts-->
            
		</div><!--end div#content-->

		
		
	</div>
		<div style="width:300px;float:right">
		<div id="sidebar">
			<div id="big_ad_2" style="margin-top:0;float:left;">{$myoutput6}</div>

			<h2 class="section_title" style="float:left;">Tags</h2>
            
            <div id="blog_tags" style="float:left;">
			{foreach ezkeywordlist( 'blog_post', $used_node.node_id ) as $keyword}
                                <a href={concat( $used_node.url_alias, "/(tag)/", $keyword.keyword|rawurlencode )|ezurl} title="{$keyword.keyword}" style="font-size:10px;">{$keyword.keyword} ({fetch( 'content', 'keyword_count', hash( 'alphabet', $keyword.keyword, 'classid', 'blog_post','parent_node_id', $used_node.node_id ) )})</a>
                            {/foreach}
            	
            </div><!--end div#blog_tags-->
			<br />
			<br />
			<a id="sidebar_site_link_1" href="#"></a>
			<a id="sidebar_site_link_2" href="#"></a>
			<h2 class="section_title" style="float:left;">special focus title</h2>
			<ul class="speedhorse_list" style="float:left;">
				{$myoutput7}
			</ul>
		</div><!-- #sidebar -->
	</div>
		
		
</div>		