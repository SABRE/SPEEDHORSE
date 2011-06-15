
	{if and(eq($pagedata.left_menu,"") , eq($pagedata.page_root_depth,0), is_set($pagedata)  )}
	<div id="main-position" style="width:95%">
      <div id="main" class="float-break">
        <div class="overflow-fix">
          <!-- Main area content: START -->
          {$module_result.content}
          <!-- Main area content: END -->
        </div>
      </div>
    </div>
	{elseif is_unset($pagedata) }
	<div id="main-position">
      <div id="main" class="float-break">
        <div class="overflow-fix">
          <!-- Main area content: START -->
          {$module_result.content}
          <!-- Main area content: END -->
        </div>
      </div>
    </div>
	{else}
	<div id="main-position">
      <div id="main" class="float-break">
        <div class="overflow-fix">
          <!-- Main area content: START -->
          {$module_result.content}
          <!-- Main area content: END -->
        </div>
      </div>
    </div>
	{/if}