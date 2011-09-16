<div>
<div><div><div></div></div></div>
<div><div><div>

<div id="basketheading">Account Information</div>
<div class="baskethead">

<ul>
    <li>{"Shopping basket"|i18n("design/ezwebin/shop/basket")}</li>
    <li class="selected">{"Account information"|i18n("design/ezwebin/shop/basket")}
</li>
    <li>{"Confirm order"|i18n("design/ezwebin/shop/basket")}</li>
</ul>


</div>

</div></div></div>
<div><div><div></div></div></div>
</div>

<div>
<div><div><div></div></div></div>
<div><div><div>

<div class="basketbdr">

<!--<div class="attribute-header">
     <h1 class="long">{"Your account information"|i18n("design/ezwebin/shop/userregister")}</h1>
</div>-->

<div class="warning">
<p>
Input did not validate. All fields marked with * must be filled in.
</p>
</div>

{section show=$input_error}
<!--<div class="warning">
<p>
{""|i18n("design/ezwebin/shop/userregister")}
</p>
</div> -->

{/section}
<form method="post" action={"/shop/userregister/"|ezurl}>
<table cellpadding="2" cellspacing="10" width="85%" class="list">
<tbody>
  <tr>
    <td width="473" valign="top"><label>Email:<span class="txtred">*</span></label></td>
    <td colspan="2" valign="top">&nbsp;</td>
  </tr>
  <tr><td colspan="3" valign="top"><div class="block">
<div class="labelbreak"></div>
<input class="box" type="text" name="EMail" size="20" value="{$email|wash}" />
</div></td></tr>
  <tr>
    <td valign="top"><label>First name:<span class="txtred">*</span></label></td>
    <td width="109" align="left" valign="top">&nbsp;</td>
    <td valign="top"><label>Last name:<span class="txtred">*</span></label></td>
  </tr>
  <tr><td colspan="2" valign="top"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="FirstName" size="20" value="{$first_name|wash}" />
    </div></td><td valign="top" width="320"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="LastName" size="20" value="{$last_name|wash}" />
    </div></td></tr>
  <tr>
    <td valign="top">Address:<span class="txtred">*</span></td>
    <td colspan="2" valign="top">Shipping Address:<span class="txtred">* </span>(Same as Billing address)
      <input name="RemoveProductItemDeleteList[]2" value="301" type="checkbox" onclick="changeshippingtext"></td>
  </tr>
  <tr><td valign="top"><label></label>
      <textarea name="Street1" id="Street1" class="box" cols="40" rows="5"></textarea>
    <td colspan="2" valign="top"><textarea name="Street2" id="Street2" class="box" cols="40" rows="5"></textarea></td>
    </tr>
  <tr>
    <td valign="top"><label>City:<span class="txtred">*</span></label></td>
    <td colspan="2" valign="top"><label>Shipping City:<span class="txtred">*</span></label></td>
  </tr>
  <tr>
    <td valign="top"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Place" size="20" value="{$place|wash}" />
    </div></td>
    <td colspan="2" valign="top"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Place1" size="20" value="{$place|wash}" />
    </div></td>
  </tr>
  <tr>
    <td valign="top">State:<span class="txtred">*</span></td>
    <td colspan="2" valign="top">Shipping State:<span class="txtred">*</span></td>
  </tr>
  <tr>
    <td valign="top"><div class="block"><div class="labelbreak"></div>
<input class="box" type="text" name="State" size="20" value="{$state|wash}" />
</div></td>
    <td colspan="2" valign="top"><div class="block"><div class="labelbreak"></div>
<input class="box" type="text" name="State1" size="20" value="{$state|wash}" />
</div></td>
  </tr>
  <tr>
    <td valign="top">Zip:<span class="txtred">*</span></td>
    <td colspan="2" valign="top">Shipping Zip:<span class="txtred">*</span></td>
  </tr>
  <tr>
    <td valign="top"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Zip" size="20" value="{$zip|wash}" />
    </div></td>
    <td colspan="2" valign="top"><div class="block"><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Zip1" size="20" value="{$zip|wash}" />
    </div></td>
  </tr>
  <tr>
    <td valign="top">Country:<span class="txtred">*</span></td>
    <td colspan="2" valign="top">Shipping Country:<span class="txtred">*</span></td>
  </tr>
  <tr>
  <td valign="top"><div class="block">
		<div class="labelbreak"></div>
			{include uri='design:shop/country/edit.tpl' select_name='Country' select_size=5 current_val=$country}
		</div></td>
		
  <td colspan="2" valign="top">
	<div class="block">
		<div class="labelbreak"></div>
			{include uri='design:shop/country/edit.tpl' select_name='Country' select_size=5 current_val=$country}
		</div>
	</td>
</tr>
 
<tr><td colspan="3" valign="top"><label>Comment:</label>  <div class="labelbreak"></div>
<textarea name="Comment" cols="80" rows="5">{$comment|wash}</textarea>
</div></td>
  </tr>
</tbody></table>
<!--<div class="block">
    <div class="element">
    <label>
    {"First name"|i18n("design/ezwebin/shop/userregister")}:*
    </label><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="FirstName" size="20" value="{$first_name|wash}" />
    </div>
    <div class="element">
    <label>
    {"Last name"|i18n("design/ezwebin/shop/userregister")}:*
    </label><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="LastName" size="20" value="{$last_name|wash}" />
    </div>
    <div class="break"></div>
</div>
<br />
<div class="block">
<label>
{"Email"|i18n("design/ezwebin/shop/userregister")}:*
</label><div class="labelbreak"></div>
<input class="box" type="text" name="EMail" size="20" value="{$email|wash}" />
</div>

<div class="block">
<label>
{"Company"|i18n("design/ezwebin/shop/userregister")}:
</label><div class="labelbreak"></div>
<input class="box" type="text" name="Street1" size="20" value="{$street1|wash}" />
</div>

<div class="block">
<label>
{"Street"|i18n("design/ezwebin/shop/userregister")}:*
</label><div class="labelbreak"></div>
<input class="box" type="text" name="Street2" size="20" value="{$street2|wash}" />
</div>

<div class="block">
    <div class="element">
    <label>
    {"Zip"|i18n("design/ezwebin/shop/userregister")}:*
    </label><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Zip" size="20" value="{$zip|wash}" />
    </div>
    <div class="element">
    <label>
    {"Place"|i18n("design/ezwebin/shop/userregister")}:*
    </label><div class="labelbreak"></div>
    <input class="halfbox" type="text" name="Place" size="20" value="{$place|wash}" />
    </div>
    <div class="break"></div>
</div>
<br/>
<div class="block">
<label>
{"State"|i18n("design/ezwebin/shop/userregister")}:
</label><div class="labelbreak"></div>
<input class="box" type="text" name="State" size="20" value="{$state|wash}" />
</div>

<div class="block">
<label>
{"Country"|i18n("design/ezwebin/shop/userregister")}:*
</label><div class="labelbreak"></div>
{include uri='design:shop/country/edit.tpl' select_name='Country' select_size=5 current_val=$country}
</div>


<div class="block">
<label>
{"Comment"|i18n("design/ezwebin/shop/userregister")}:
</label><div class="labelbreak"></div>
<textarea name="Comment" cols="80" rows="5">{$comment|wash}</textarea>
</div>

 -->
<div class="buttonblock">
  <input type="image" name="CancelButton" src="http://sandbox.speedhorse.com/images/cancel.jpg">
   <input type="image" name="StoreButton" src="http://sandbox.speedhorse.com/images/continue2.jpg">

<!--    <input class="button" type="submit" name="CancelButton" value="{"Cancel"|i18n('design/ezwebin/shop/userregister')}" />
    <input class="defaultbutton" type="submit" name="StoreButton" value="{"Continue"|i18n( 'design/ezwebin/shop/userregister')}" />
--></div>

</form>

<!--<p>
{"All fields marked with * must be filled in."|i18n("design/ezwebin/shop/userregister")}
</p>
-->
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>