{* Comment - Line view *}

<div class="content-view-line">
    <div class="class-comment">

    <h2>{$node.name|wash}</h2>

    <div class="attribute-byline float-break">
        <p class="date">{$node.object.published|l10n(datetime)}</p>
		
        <p class="author"><a href="http://sandbox.speedhorse.com/userdetail/list/{$node.creator.id}">{$node.data_map.author.content|wash}</a></p>
    </div>

    <div class="attribute-message">
        <p>{$node.data_map.message.content|wash(xhtml)|break}</p>
    </div>

    </div>
</div>