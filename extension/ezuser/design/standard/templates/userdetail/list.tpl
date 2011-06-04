<table width="600" border="0" cellpadding="5" cellspacing="5">
<tr>
    <td width="170">Username/E-mail: </td>
    <td width="291" colspan="2">{$email}</td>
    
  </tr>

  <tr>
    <td width="170">First name: </td>
    <td width="291">{$first_name}</td>
    <td width="334" rowspan="5"><img src="{$imagePath}" style="border:none;"/></td>
  </tr>
  <tr>
    <td>Middle name: </td>
    <td>{$middle_name}</td>
  </tr>
  <tr>
    <td>Last name: </td>
    <td>{$last_name}</td>
  </tr>
  <tr>
    <td>Office phone:</td>
    <td>{$office_phone_number}</td>
  </tr>
  <tr>
    <td>Cell phone:</td>
    <td>{$cell_phone_number}</td>
  </tr>
  <tr>
    <td>Home phone:</td>
    <td colspan="2">{$home_phone_number}</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Billing address </strong></td>
  </tr>
  <tr>
    <td>Address:</td>
    <td colspan="2">{$address}</td>
  </tr>
  <tr>
    <td>City:</td>
    <td colspan="2">{$city}</td>
  </tr>
  <tr>
    <td>State:</td>
    <td colspan="2">{$state}</td>
  </tr>
  <tr>
    <td>Zip:</td>
    <td colspan="2">{$zip}</td>
  </tr>
  <tr>
    <td>Country:</td>
    <td colspan="2">{$country}</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Shipping Address</strong></td>
  </tr>
  <tr>
    <td>Address:</td>
    <td colspan="2">{$saddress}</td>
  </tr>
  <tr>
    <td>City:</td>
    <td colspan="2">{$scity}</td>
  </tr>
  <tr>
    <td>State:</td>
    <td colspan="2">{$sstate}</td>
  </tr>
  <tr>
    <td>Zip:</td>
    <td colspan="2">{$szip}</td>
  </tr>
  <tr>
    <td>Country:</td>
    <td colspan="2">{$scountry}</td>
  </tr>
{def $con='   '}
  <tr>
    <td colspan="3">Articles: &nbsp;&nbsp;
	{if is_set($data_array)} 
	{foreach $data_array as $index => $item}	
	<a href="http://sandbox.speedhorse.com/{$item.path}">{$item.name}</a>{$con}
	{/foreach}
	{/if}

	</td>
  </tr>
      


  <tr>
    <td colspan="3"></td>
  </tr>
 
  <tr>
    <td colspan="3">Comments:&nbsp;&nbsp;{if is_set($data_array1)} {foreach $data_array1 as $index => $item}<a href="http://sandbox.speedhorse.com/{$item.path}">{$item.name}</a>{$con}{/foreach}{/if} </td>
  </tr>

<tr><td colspan="3"></td></tr>
</table>

 
 