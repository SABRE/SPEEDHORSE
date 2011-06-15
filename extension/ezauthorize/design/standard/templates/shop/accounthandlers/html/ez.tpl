{* ezdbug_dump($order.account_information) *}
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td valign="top">
	<b>{"Customer"|i18n("design/standard/shop")}</b>
	</p>
	<p>
	{'Name'|i18n('design/standard/shop')}: {$order.account_information.first_name} {$order.account_information.last_name}<br />
	{'Email'|i18n('design/standard/shop')}: {$order.account_information.email}<br />
</td>
<td valign="top" width="300px;">
{if eq($order.account_information.shipping,1)}
<b>{"Address"|i18n("design/standard/shop")}</b>
<table border="0"  cellspacing="0" cellpadding="0">
<tr><td>{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
{if gt(count($order.account_information.address2),0)}
<tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
{/if}
<tr><td>{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
<tr><td>{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
<tr><td>{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
<tr><td>{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
<tr><td>{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
<tr><td>{'Shipping'|i18n('design/standard/shop')}:</td><td>
{switch match=$order.account_information.shippingtype}
{case match="1"}
    Next Day Service
{/case}
{case match="2"}
    2nd Day Service
{/case}
{case}
    Standard Shipping
{/case}
{/switch}
</td></tr>
</table>

{else}

<b>{"Billing Address"|i18n("design/standard/shop")}</b>
<table border="0"  cellspacing="0" cellpadding="0">
<tr><td>{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
{if gt(count($order.account_information.address2),0)}
<tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
{/if}
<tr><td>{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
<tr><td>{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
<tr><td>{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
<tr><td>{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
<tr><td>{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
<tr><td>{'Shipping'|i18n('design/standard/shop')}:</td><td>
{switch match=$order.account_information.shippingtype}
{case match="1"}
    Next Day Service
{/case}
{case match="2"}
    2nd Day Service
{/case}
{case}
    Standard Shipping
{/case}
{/switch}

</td></tr>
</table>

{/if}
</td>
</tr>
</table>
