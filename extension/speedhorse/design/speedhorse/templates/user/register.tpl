<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="user-register">

<form enctype="multipart/form-data"  action={"/user/register/"|ezurl} method="post" name="Register" onsubmit="return checked(this)">

<div class="attribute-header">
    <h1 class="long">{"Register user"|i18n("design/ezwebin/user/register")}</h1>
</div>

{if and( and( is_set( $checkErrNodeId ), $checkErrNodeId ), eq( $checkErrNodeId, true() ) )}
    <div class="message-error">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$errMsg}</h2>
    </div>
{/if}

{if $validation.processed}

    {if $validation.attributes|count|gt(0)}
        <div class="warning">
        <h2>{"Input did not validate"|i18n("design/ezwebin/user/register")}</h2>
        <ul>
        {foreach $validation.attributes as $attribute}
            <li>{$attribute.name}: {$attribute.description}</li>
        {/foreach}
        </ul>
        </div>
    {else}
        <div class="feedback">
        <h2>{"Input was stored successfully"|i18n("design/ezwebin/user/register")}</h2>
        </div>
    {/if}

{/if}

{if count($content_attributes)|gt(0)}
<table width="95%" cellpadding="0" cellspacing="5">
    {foreach $content_attributes as $attribute}
	
	{if $attribute.contentclass_attribute.name|eq('User account')}	
	<tr><td valign="top" colspan="3"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div></td></tr>
		
		{elseif $attribute.contentclass_attribute.name|eq('User ID')}
	<tr><td valign="top" colspan="3"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td></tr>
	
	{elseif $attribute.contentclass_attribute.name|eq('Username')}
	<tr><td valign="top">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td>
	
	{elseif $attribute.contentclass_attribute.name|eq('Password')}
	<td valign="top" >
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td>
		
		{elseif $attribute.contentclass_attribute.name|eq('Confirm password')}
	<td valign="top" >
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td></tr>
	
	{elseif $attribute.contentclass_attribute.name|eq('First name')}	
	   <tr><td valign="top"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div></td>

		
		{elseif $attribute.contentclass_attribute.name|eq('Middle name')}
	 <td valign="top" ><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td>
		
		{elseif $attribute.contentclass_attribute.name|eq('Last name')}
	<td valign="top" ><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <div class="block">
        <label>{$attribute.contentclass_attribute.name}*</label><div class="labelbreak"></div>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td>
		</tr>
		
	  	
	{elseif $attribute.contentclass_attribute.name|eq('Work phone number')}	
	<tr><td valign="top"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   	<div class="block">
         <label>{$attribute.contentclass_attribute.name}</label>
        {attribute_edit_gui attribute=$attribute}
    </div></td>
		
	 		
		{elseif $attribute.contentclass_attribute.name|eq('Cell phone number')}
	<td valign="top"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
	 <div class="block">
	 <label>{$attribute.contentclass_attribute.name}</label>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td>
		
		 	
		{elseif $attribute.contentclass_attribute.name|eq('Home phone number')}	
	<td valign="top"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
 	 <div class="block">
       <label>{$attribute.contentclass_attribute.name}</label>
        {attribute_edit_gui attribute=$attribute}
    </div>
		</td></tr>

	<tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('Address')}
	<div style="width:49%; float:left"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   
      <label>{$attribute.contentclass_attribute.name}*</label>
        {attribute_edit_gui attribute=$attribute}
   
	</div>
	{elseif $attribute.contentclass_attribute.name|eq('Shipping address')}
	<div style="width:49%; float:right;">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   <label>{$attribute.contentclass_attribute.name}<input type="checkbox" id="shippingid" name="shippingid" onclick="myfill()" /><font size="-4">(Same as Billing address)</font></label>{attribute_edit_gui attribute=$attribute}
  	</div>
	</td></tr>
	

<tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('City')}
	<div style="width:49%; float:left"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   
      <label>{$attribute.contentclass_attribute.name}*</label>
        {attribute_edit_gui attribute=$attribute}
   
	</div>
	{elseif $attribute.contentclass_attribute.name|eq('Shipping city')}
	<div style="width:49%; float:right;">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   <label>{$attribute.contentclass_attribute.name}</label>{attribute_edit_gui attribute=$attribute}
  	</div>
	</td></tr>


<tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('State')}
	<div style="width:49%; float:left"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   
      <label>{$attribute.contentclass_attribute.name}*</label>
        {attribute_edit_gui attribute=$attribute}
   
	</div>
	{elseif $attribute.contentclass_attribute.name|eq('Shipping state')}
	<div style="width:49%; float:right;">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   <label>{$attribute.contentclass_attribute.name}</label>{attribute_edit_gui attribute=$attribute}
  	</div>
	</td></tr>

<tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('Zip')}
	<div style="width:49%; float:left"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   
     <label> {$attribute.contentclass_attribute.name}*</label>
        {attribute_edit_gui attribute=$attribute}
   
	</div>
	{elseif $attribute.contentclass_attribute.name|eq('Shipping zip')}
	<div style="width:49%; float:right;">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   <label>{$attribute.contentclass_attribute.name}</label>{attribute_edit_gui attribute=$attribute}
  	</div>
	</td></tr>
	
	<tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('Country')}
	<div style="width:49%; float:left"><input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <label>{$attribute.contentclass_attribute.name}*</label>
    {attribute_edit_gui attribute=$attribute}
    </div>
	
	{elseif $attribute.contentclass_attribute.name|eq('Shipping country')}
	<div style="width:49%; float:right;">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <label>{$attribute.contentclass_attribute.name}</label>{attribute_edit_gui attribute=$attribute}
  	</div>
	</td></tr>

    <tr><td valign="top" colspan="3"> 
	{elseif $attribute.contentclass_attribute.name|eq('Signature')}
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
   
    <label>{$attribute.contentclass_attribute.name}</label>
    {attribute_edit_gui attribute=$attribute}
    </td></tr>  
	
	{elseif $attribute.contentclass_attribute.name|eq('Image')}
	<tr><td colspan="3" valign="top">
	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
    <label>{$attribute.contentclass_attribute.name}</label>{attribute_edit_gui attribute=$attribute}
  	</td></tr>
			
	{/if}
	{/foreach}
</table>	
    <div class="buttonblock">
         <input class="button" type="hidden" name="UserID" value="{$content_attributes[0].contentobject_id}" />
    {if and( is_set( $checkErrNodeId ), $checkErrNodeId )|not()}
        <input class="button" type="submit" id="PublishButton" name="PublishButton" value="{'Register'|i18n('design/ezwebin/user/register')}" />
    {else}    
           <input class="button" type="submit" id="PublishButton" name="PublishButton" disabled="disabled" value="{'Register'|i18n('design/ezwebin/user/register')}" />
    {/if}
    <input class="button" type="submit" id="CancelButton" name="CancelButton" value="{'Discard'|i18n('design/ezwebin/user/register')}" />
    </div>
{else}
    <div class="warning">
         <h2>{"Unable to register new user"|i18n("design/ezwebin/user/register")}</h2>
    </div>
    <input class="button" type="submit" id="CancelButton" name="CancelButton" value="{'Back'|i18n('design/ezwebin/user/register')}"  />
{/if}
</form>
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

{literal}
<script language="JavaScript" type="text/javascript">
<!--

function getElementsByType(array, type) {
	var result=new Array();
	for(i=0;i<array.length;i++) { if(array[i].type==type)result.push(array[i]); }
	return result;
}


    function disableButtons()
    {
        document.getElementById( 'PublishButton' ).disabled = true;
        document.getElementById( 'CancelButton' ).disabled = true;
    }
	
	function myfill()
	{
		if(document.getElementById('shippingid').checked==true)
			{
				document.getElementById('ezcoa-479_saddress').value=document.getElementById('ezcoa-478_address').value;
				document.getElementById('ezcoa-480_scity').value=document.getElementById('ezcoa-473_city').value;
				document.getElementById('ezcoa-482_sstate').value=document.getElementById('ezcoa-481_state').value;
				document.getElementById('ezcoa-484_szip').value=document.getElementById('ezcoa-483_zip').value;
				document.getElementById('ezcoa-486_scountry').value=document.getElementById('ezcoa-485_country').value;
			}
			else
				{
				document.getElementById('ezcoa-479_saddress').value="";
				document.getElementById('ezcoa-480_scity').value="";
				document.getElementById('ezcoa-482_sstate').value="";
				document.getElementById('ezcoa-484_szip').value="";
				document.getElementById('ezcoa-486_scountry').value="";
				}
	}
	
	function checked(form)
	{
		
								
		if((document.getElementById('ezcoa-467_office_phone_number').value=='') && (document.getElementById('ezcoa-469_home_phone_number').value=='') && (document.getElementById('ezcoa-468_cell_phone_number').value==''))
			{
				alert("Please select at least one contact phone no.");
				return false;
			}	
					//}//for loop
		return true;
	}				
	
	function workfill()
	{
		document.getElementById('ezcoa-467_office_phone_number')
	}
-->
</script>
{/literal}
