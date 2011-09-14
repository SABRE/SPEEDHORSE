	<div id="main1"><!--start div#main-->
	  <!--end ul#subnav-->
	  <!--end div#midle contents-->
	  
	  <h2 >Confirm order</h2>

<!--div#baskethead contents-->	
	<div class="baskethead">
		<ul>
			<li>{"Shopping basket"|i18n("design/ezwebin/shop/confirmorder")}</li>
			<li> {"Account information"|i18n("design/ezwebin/shop/confirmorder")}</li>
			<li  class="selected">{"Confirm order"|i18n("design/ezwebin/shop/confirmorder")}</li>
		</ul>
	</div>

<!--end div#baskethead contents end-->
	
<!--div#basketbdr contents-->
	
	<div class="basketbdr" >
	
	<form method="post" action={"/shop/confirmorder/"|ezurl}>
	
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td valign="top"><b class="section_title">Customer</b>
          <p></p>
        <p><!--{shop_account_view_gui view=html order=$order} --></p>
		</td>
		{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}
 <td valign="top" width="300px;"><span ><b class="section_title">Billing Address</b> </span>
          <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td>{shop_account_view_gui view=html order=$order} </td>
                <td></td>
              </tr>
            </tbody>
        </table></td>
      <td valign="top" width="300px;"><span ><b class="section_title">shipping  Address</b> </span>
          <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
              <tr>
                <td>{shop_account_view_gui view=html order=$order}</td>
                <td></td>
              </tr>
            </tbody>
          </table></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top"><span class="buttonblock">
        <input type="image" name="EditButton" src="http://collegeyardart.com/images/edit.jpg">
      </span></td>
    </tr>
  </tbody>
</table>
<b class="section_title">{"Product items"|i18n("design/ezwebin/shop/confirmorder")}</b>
<table class="list" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <th width="37%"> {"Product Description"|i18n("design/ezwebin/shop/confirmorder")}</th>
      <th width="14%"> {"Quantity"|i18n("design/ezwebin/shop/confirmorder")} </th>
      <th width="14%"> {"Item Price"|i18n("design/ezwebin/shop/confirmorder")} </th>
      <th width="14%"> {"Item Total"|i18n("design/ezwebin/shop/confirmorder")} </th>
      <th width="23%" align="right">&nbsp;</th>
    </tr>
	
    <tr class="bglight">
      <td colspan="5">&nbsp;</td>
    </tr>
{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
	
    <tr class="bgdark">
      <td><input type="hidden" name="ProductItemIDList[]" value="{$ProductItem:item.id}" />
    <a href={concat("/content/view/full/",$ProductItem:item.node_id,"/")|ezurl}>{$ProductItem:item.object_name}</a></td>
      <td align="center">{$ProductItem:item.item_count}</td>
      <td> {$ProductItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
      <td>{$ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
      <td><div align="right"><span class="buttonblock">
	  	<!--<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$ProductItem:item.id}" /> -->
        <input type="image" name="CancelButton" src="http://collegeyardart.com/images/update.jpg">
        <input type="image" name="CancelButton" src="http://collegeyardart.com/images/remove.jpg">
      </span></div></td>
    </tr>
    {/section}

    <tr>
      <td colspan="5"><hr size="1" style="color:#ebe4d4"></td>
    </tr>
    <tr>
      <td colspan="5"><table width="17%" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2"><div align="right"><b class="section_title">{"Order summary"|i18n("design/ezwebin/shop/confirmorder")}</b></div></td>
          </tr>
        <tr>
          <td width="66%"><div align="right"><strong>{"SubTotal"|i18n("design/ezwebin/shop/confirmorder")}</strong></div></td>
          <td width="34%"><div align="right">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Tax</strong></div></td>
          <td><div align="right">
		  {sub($order.product_total_inc_vat,$order.product_total_ex_vat)|l10n( 'currency', $locale, $symbol )}
		  </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Total</strong></div></td>
          <td><div align="right">{$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}</div></td>
        </tr>
      </table></td>
    </tr>
  </tbody>
</table>
<div class="buttonblock">
  <div align="right">
    <input type="image" name="CancelButton" src="http://collegeyardart.com/images/cancel.jpg">
    &nbsp; 
    <input type="image" name="ConfirmOrderButton" src="http://collegeyardart.com/images/confirm.jpg">
  </div>
</div>
</form>
</div>
</div>

	