                            <div class="attribute-archive">
                            <h1>{*"Archive"|i18n("design/ezwebin/blog/extra_info")*}</h1>
                            <ul style="float:left;" class="blog_archives_my">
                            {foreach ezarchive( 'blog_post', $used_node.node_id ) as $archive}
                            <li><a href={concat( $used_node.url_alias, "/(month)/", $archive.month, "/(year)/", $archive.year )|ezurl} title="">{$archive.timestamp|datetime( 'custom', '%F %Y' )}</a></li>
                            {/foreach}
                            </ul>
                        </div>

