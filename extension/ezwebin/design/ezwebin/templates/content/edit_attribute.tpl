{default $view_parameters            = array()
         $attribute_categorys        = ezini( 'ClassAttributeSettings', 'CategoryList', 'content.ini' )
         $attribute_default_category = ezini( 'ClassAttributeSettings', 'DefaultCategory', 'content.ini' )}
<table cellpadding="0" cellspacing="5" width="85%">
{foreach $content_attributes_grouped_data_map as $attribute_group => $content_attributes_grouped}
{if $attribute_group|ne( $attribute_default_category )}
	<fieldset class="ezcca-collapsible">
	<legend><a href="JavaScript:void(0);">{$attribute_categorys[$attribute_group]}</a></legend>
	<div class="ezcca-collapsible-fieldset-content">
{/if}
{foreach $content_attributes_grouped as $attribute_identifier => $attribute}

{if $attribute_identifier|eq('user_account')}
<tr><td colspan="3" valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td></tr>


{elseif $attribute_identifier|eq('first_name')}
<tr><td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>

{elseif $attribute_identifier|eq('middle_name')}
<td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>

{elseif $attribute_identifier|eq('last_name')}
<td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td></tr>

<!---for phone no-->

{elseif $attribute_identifier|eq('office_phone_number')}
<tr><td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>

{elseif $attribute_identifier|eq('cell_phone_number')}
<td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>

{elseif $attribute_identifier|eq('home_phone_number')}
<td valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td></tr>


<!---end of phone tr-->

<!---start of address tr-->


{elseif $attribute_identifier|eq('address')}
<tr><td valign="top" colspan="3">
<div style="width:49%; float:left">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>

{elseif $attribute_identifier|eq('saddress')}
<div style="width:49%; float:right">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}: <input type="checkbox" id="shippingid" name="shippingid" onclick="myfill()" /><font size="-4">(Same as Billing address)</font>
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>
</td>
</tr>


<!---end of address tr-->



<!---start of city tr-->


{elseif $attribute_identifier|eq('city')}
<tr><td valign="top" colspan="3">
<div style="width:49%; float:left">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>

{elseif $attribute_identifier|eq('scity')}
<div style="width:49%; float:right">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>
</td>
</tr>


<!---end of city tr-->

<!---start of state tr-->


{elseif $attribute_identifier|eq('state')}
<tr><td valign="top" colspan="3">
<div style="width:49%; float:left">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>

{elseif $attribute_identifier|eq('sstate')}
<div style="width:49%; float:right">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>
</td>
</tr>

<!---end of state tr-->

<!---start of zip tr-->


{elseif $attribute_identifier|eq('zip')}
<tr><td valign="top" colspan="3">
<div style="width:49%; float:left">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>

{elseif $attribute_identifier|eq('szip')}
<div style="width:49%; float:right">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>
</td>
</tr>


<!---end of zip tr-->

<!---start of country tr-->


{elseif $attribute_identifier|eq('country')}
<tr><td valign="top" colspan="3">
<div style="width:49%; float:left">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>

{elseif $attribute_identifier|eq('scountry')}
<div style="width:49%; float:right">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</div>
</td>
</tr>


<!---end of country tr-->


<!---start of signature tr-->


{elseif $attribute_identifier|eq('signature')}
<tr><td valign="top" colspan="3">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>

</td></tr>
{elseif $attribute_identifier|eq('image')}
<tr><td colspan="3" valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>
</tr>


<!---end of image tr-->


</td></tr>


{else}
<tr><td colspan="3" valign="top">
{def $contentclass_attribute = $attribute.contentclass_attribute}
<div class="block ezcca-edit-datatype-{$attribute.data_type_string} ezcca-edit-{$attribute_identifier}">
{* Show view GUI if we can't edit, otherwise: show edit GUI. *}
{if and( eq( $attribute.can_translate, 0 ), ne( $object.initial_language_code, $attribute.language_code ) )}
    <label>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
        {if $attribute.can_translate|not} <span class="nontranslatable">({'not translatable'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
        {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
    </label>
    {if $is_translating_content}
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        </div>
    {else}
        {attribute_view_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    {/if}
{else}
    {if $is_translating_content}
        <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
            {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
            {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
            {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
        </label>
        <div class="original">
        {attribute_view_gui attribute_base=$attribute_base attribute=$from_content_attributes_grouped_data_map[$attribute_group][$attribute_identifier] view_parameters=$view_parameters}
        </div>
        <div class="translation">
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
        </div>
    {else}
        {if $attribute.display_info.edit.grouped_input}
            <fieldset>
            <legend{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">({'required'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </legend>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
            </fieldset>
        {else}
            <label{if $attribute.has_validation_error} class="message-error"{/if}>{first_set( $contentclass_attribute.nameList[$content_language], $contentclass_attribute.name )|wash}
                {if $attribute.is_required} <span class="required">*{*'required'|i18n( 'design/admin/content/edit_attribute' )*}</span>{/if}
                {if $attribute.is_information_collector} <span class="collector">({'information collector'|i18n( 'design/admin/content/edit_attribute' )})</span>{/if}:
                {if $contentclass_attribute.description} <span class="classattribute-description">{first_set( $contentclass_attribute.descriptionList[$content_language], $contentclass_attribute.description)|wash}</span>{/if}
            </label>
            {attribute_edit_gui attribute_base=$attribute_base attribute=$attribute view_parameters=$view_parameters}
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
        {/if}
    {/if}
{/if}
</div>
</td>
</tr>



{/if}
{undef $contentclass_attribute}
{/foreach}
{if $attribute_group|ne( $attribute_default_category )}
    </div>
    </fieldset>
{/if}
{/foreach}
</table>
{run-once}
{* if is_set( $content_attributes_grouped_data_map[1] ) *}
{ezscript_require(array( 'ezjsc::jquery' ) )}
<script type="text/javascript">
<!--
{literal}

jQuery(function( $ )
{
    $('fieldset.ezcca-collapsible legend a').click( function()
    {
		var container = $( this.parentNode.parentNode ), inner = container.find('div.ezcca-collapsible-fieldset-content');
		if ( container.hasClass('ezcca-collapsed') )
		{
			container.removeClass('ezcca-collapsed');
			inner.slideDown( 150 );
	    }
		else
		{
			inner.slideUp( 150, function(){
            	$( this.parentNode ).addClass('ezcca-collapsed');
            });
        }
    });
    // Collapse by default
    $('fieldset.ezcca-collapsible').addClass('ezcca-collapsed').find('div.ezcca-collapsible-fieldset-content').hide();
});


function myfill()
	{
		if(document.getElementById('shippingid').checked==true)
			{
				document.getElementById('ezcoa-554_saddress').value=document.getElementById('ezcoa-549_address').value;
				document.getElementById('ezcoa-555_scity').value=document.getElementById('ezcoa-550_city').value;
				document.getElementById('ezcoa-556_sstate').value=document.getElementById('ezcoa-551_state').value;
				document.getElementById('ezcoa-557_szip').value=document.getElementById('ezcoa-552_zip').value;
				document.getElementById('ezcoa-560_scountry').value=document.getElementById('ezcoa-559_country').value;
			}
			else
				{
				document.getElementById('ezcoa-554_saddress').value="";
				document.getElementById('ezcoa-555_scity').value="";
				document.getElementById('ezcoa-556_sstate').value="";
				document.getElementById('ezcoa-557_szip').value="";
				document.getElementById('ezcoa-560_scountry').value="";
				}
	}
{/literal}
-->
</script>
{* /if *}
{/run-once}
{/default}
