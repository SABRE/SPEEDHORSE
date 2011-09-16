<div id="main1"><!--start div#main-->
	  <!--end ul#subnav-->
	  <!--end div#midle contents-->
	  <h2>Basket</h2>
<!--div#baskethead contents-->	
	<div class="baskethead">
		<ul>
			<li class="selected">{"Shopping basket"|i18n("design/ezwebin/shop/basket")}</li>
			<li>{"Account information"|i18n("design/ezwebin/shop/basket")}</li>
			<li>{"Confirm order"|i18n("design/ezwebin/shop/basket")}</li>
		</ul>
	</div>
<!--end div#baskethead contents end-->
<!--div#basketbdr contents-->
	<div class="basketbdr" >
	<form method="post" action={"/shop/basket/"|ezurl}>

{section show=$removed_items}
<div class="warning">
    <h2>{"The following items were removed from your basket because the products were changed."|i18n("design/ezwebin/shop/basket",,)}</h2>
    <ul>
    {section name=RemovedItem loop=$removed_items}
        <li> <a href={concat("/content/view/full/",$RemovedItem:item.contentobject.main_node_id,"/")|ezurl}>{$RemovedItem:item.contentobject.name|wash}</a></li>
    {/section}
    </ul>
</div>
{/section}

{if not( $vat_is_known )}
<div class="message-warning">
<h2>{'VAT is unknown'|i18n( 'design/ezwebin/shop/basket' )}</h2>
{'VAT percentage is not yet known for some of the items being purchased.'|i18n( 'design/ezwebin/shop/basket' )}<br/>
{'This probably means that some information about you is not yet available and will be obtained during checkout.'|i18n( 'design/ezwebin/shop/basket' )}
</div>
{/if}

{section show=$error}
<div class="error">
{section show=$error|eq(1)}
<h2>{"Attempted to add object without price to basket."|i18n("design/ezwebin/shop/basket",,)}</h2>
{/section}
</div>
{/section}

{section show=$error}
<div class="error">
{section show=eq($error, "aborted")}
<h2>{"Your payment was aborted."|i18n("design/ezwebin/shop/basket",,)}</h2>
{/section}
</div>
{/section}

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    {section name=Basket show=$basket.items}
	
<table class="list" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody><tr>
    <th width="38%">{"Product Description"|i18n("design/ezwebin/shop/basket")}</th>
    <th width="14%">{"Quantity"|i18n("design/ezwebin/shop/basket")}</th>
    <th width="19%">{"Item Price"|i18n("design/ezwebin/shop/basket")}</th>
    <th width="16%">{"Item Total"|i18n("design/ezwebin/shop/basket")}</th>
    <th width="13%" align="right">{"Checkbox"|i18n("design/ezwebin/shop/basket")}</th>
</tr>
{section name=ProductItem loop=$basket.items sequence=array(bgdark, bglight)}
<tr>
    <td colspan="5"><input type="hidden" name="ProductItemIDList[]" value="{$Basket:ProductItem:item.id}" /></td>
	{*{$Basket:ProductItem:item.id}-*}
</tr>
<tr class="bgdark">
    <td>
      {$Basket:ProductItem:item.object_name}
    </td>
    <td><input type="text" name="ProductItemCountList[]" value="{$Basket:ProductItem:item.item_count}" size="5" /></td>
    <td>{$Basket:ProductItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td>{$Basket:ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}</td>
    <td><div align="right">
	<input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$Basket:ProductItem:item.id}" />
    </div></td>
    </tr>
{/section}
<tr class="bglight">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><div align="right"><span class="buttonblock">
      <input type="image" name="StoreChangesButton" src="http://sandbox.speedhorse.com/images/update.jpg">
      <input type="image" name="RemoveProductItemButton" src="http://sandbox.speedhorse.com/images/remove.jpg">
    </span></div></td>
    </tr>
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td colspan="2"><div align="right">* select items above for update or remove </div></td>
  </tr>
<tr>
  <td colspan="5"><hr size="1" style="color:#ebe4d4"></td>
</tr>

<tr>
     <td colspan="5"><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td width="91%"><div align="right"><strong>{"Subtotal :    "|i18n("design/ezwebin/shop/basket")}</strong></div></td>
         <td width="9%"><div align="right">{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</div></td>
       </tr>
       <tr>
         <td><div align="right"><strong>{"Tax :    "|i18n("design/ezwebin/shop/basket")}</strong></div></td>
         <td><div align="right">{* $basket.vat_value|l10n( 'currency', $locale, $symbol ) *}

	 {sub($basket.total_inc_vat,$basket.total_ex_vat)|l10n( 'currency', $locale, $symbol )}</div></td>
       </tr>
       <tr>
         <td><div align="right"><strong>{"Total :    "|i18n("design/ezwebin/shop/basket")}</strong></div></td>
         <td><div align="right">{$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}</div></td>
       </tr>
     </table></td>
</tr>
</tbody></table>

<div class="buttonblock">
  <div align="right">
  <input type="image" name="ContinueShoppingButton" src="http://sandbox.speedhorse.com/images/continue2.jpg">&nbsp;
  <input type="image" name="CheckoutButton" src="http://sandbox.speedhorse.com/images/chekout.jpg">
  </div>
</div>

{undef $currency $locale $symbol}
<!--<div class="feedback">
<h2>{"You have no products in your basket."|i18n("design/ezwebin/shop/basket")}</h2>
</div> -->
</form>
        
	</div>
	  
	  
	  
<!--end div#midle contents-->
        
</div><!-- #main -->