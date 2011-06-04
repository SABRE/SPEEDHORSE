<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="shop-userregister">

<ul>
     <li>1. {"Shopping basket"|i18n("design/ezwebin/shop/userregister")}</li>
     <li class="selected">2. {"Account information"|i18n("design/ezwebin/shop/userregister")}
</li>
     <li>3. {"Confirm order"|i18n("design/ezwebin/shop/userregister")}</li>
</ul>

</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="shop-userregister">

<div class="attribute-header">
     <h1 class="long">{"Shipping Information"|i18n("design/ezwebin/shop/userregister")}</h1>
</div>

{section show=$input_error}
<div class="warning">
<p>
{"Input did not validate. All fields marked with * must be filled in."|i18n("design/ezwebin/shop/userregister")}
</p>
</div>

{/section}
<form method="post" action={"/shop/userregister/"|ezurl}>

<table cellpadding="0" cellspacing="10" width="85%">
<tr><td width="10%" valign="top" ><label>{"Email"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top" colspan="3" width="90"><input class="box" type="text" name="EMail" size="5" value="{$email|wash}" /></td></tr>
<tr><td valign="top" width="10%"><label>{"First name"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td width="40%" valign="top" align="left"><input class="box" type="text" name="FirstName" size="5" value="{$first_name|wash}" /></td><td width="10%" align="right" valign="top"><label>{"Last name"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td width="40%" valign="top"><input class="box" type="text" name="LastName" size="5" value="{$last_name|wash}" /></td></tr>
<tr><td valign="top"><label>{"Address"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top" colspan="3"><textarea name="Street2" class="box" cols="80" rows="5">{$street2|wash}</textarea>
<input type="hidden" value="" name="Street1" id="Street1" /></td></tr>
<tr><td valign="top"><label>{"City"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top"><input class="box" type="text" name="Place" size="5" value="{$place|wash}" /></td><td valign="top"><label>{"State"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top"><input class="box" type="text" name="State" size="5" value="{$state|wash}" /></td></tr>
<tr><td valign="top"><label>{"Zip"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top"><input class="box" type="text" name="Zip" size="5" value="{$zip|wash}" /></td><td valign="top"><label>{"Country"|i18n("design/ezwebin/shop/userregister")}:*</label></td><td valign="top">{include uri='design:shop/country/edit.tpl' select_name='Country'  current_val=$country}</td></tr>
<tr><td valign="top"><label>{"Comment"|i18n("design/ezwebin/shop/userregister")}:</label></td>
  <td valign="top" colspan="3"><textarea name="Comment" class="box" cols="80" rows="5">{$comment|wash}</textarea></td>
</tr>
</table>


<div class="buttonblock">
    <input class="button" type="submit" name="CancelButton" value="{"Cancel"|i18n('design/ezwebin/shop/userregister')}" />
    <input class="defaultbutton" type="submit" name="StoreButton" value="{"Continue"|i18n( 'design/ezwebin/shop/userregister')}" />
</div>

</form>

<p>
{"All fields marked with * must be filled in."|i18n("design/ezwebin/shop/userregister")}
</p>

</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>