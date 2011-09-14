<table width="600" border="0" cellpadding="5" cellspacing="5">
<tr>
    <td width="170"><strong>Username/E-mail:</strong> </td>
    <td width="291" colspan="2">{$email}</td>
    
  </tr>

  <tr>
    <td width="170"><strong>First name:</strong> </td>
    <td width="291">{$first_name}</td>
    <td width="334" rowspan="5"><img src="http://collegeyardart.com/{$imagePath}" style="border:none;" width="100px" height="100px"/></td>
  </tr>
  <tr>
    <td><strong>Middle name:</strong> </td>
    <td>{$middle_name}</td>
  </tr>
  <tr>
    <td><strong>Last name:</strong> </td>
    <td>{$last_name}</td>
  </tr>
  <tr>
    <td><strong>Office phone:</strong></td>
    <td>{$office_phone_number}</td>
  </tr>
  <tr>
    <td><strong>Cell phone:</strong></td>
    <td>{$cell_phone_number}</td>
  </tr>
  <tr>
    <td><strong>Home phone:</strong></td>
    <td colspan="2">{$home_phone_number}</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Billing address </strong></td>
  </tr>
  <tr>
    <td><strong>Address:</strong></td>
    <td colspan="2">{$address}</td>
  </tr>
  <tr>
    <td><strong>City:</strong></td>
    <td colspan="2">{$city}</td>
  </tr>
  <tr>
    <td><strong>State:</strong></td>
    <td colspan="2">{$state}</td>
  </tr>
  <tr>
    <td><strong>Zip:</strong></td>
    <td colspan="2">{$zip}</td>
  </tr>
  <tr>
    <td><strong>Country:</strong></td>
    <td colspan="2">{$country}</td>
  </tr>
  <tr>
    <td colspan="3"><strong>Shipping Address</strong></td>
  </tr>
  <tr>
    <td><strong>Address:</strong></td>
    <td colspan="2">{$saddress}</td>
  </tr>
  <tr>
    <td><strong>City:</strong></td>
    <td colspan="2">{$scity}</td>
  </tr>
  <tr>
    <td><strong>State:</strong></td>
    <td colspan="2">{$sstate}</td>
  </tr>
  <tr>
    <td><strong>Zip:</strong></td>
    <td colspan="2">{$szip}</td>
  </tr>
  <tr>
    <td><strong>Country:</strong></td>
    <td colspan="2">{$scountry}</td>
  </tr>
{def $con='   '}
  <tr>
    <td colspan="3"><strong>Articles:</strong> &nbsp;&nbsp;
	{if is_set($data_array)} 
	{foreach $data_array as $index => $item}	
	<a href="http://collegeyardart.com/index.php/{$item.path}">{$item.name}</a>{$con}
	{/foreach}
	{/if}

	</td>
  </tr>
   
   <tr>
    <td height="33" colspan="15"></td>
  </tr>
      
<tr>
    <td height="67" colspan="3"><strong>Blogs:</strong> &nbsp;&nbsp;
	{if is_set($data_array2)} 
	{foreach $data_array2 as $index => $item}	
	<a href="http://collegeyardart.com/index.php/{$item.path}">{$item.name}</a>{$con}
	{/foreach}
	{/if}

	</td>
  </tr>


  <tr>
    <td height="39" colspan="15"></td>
  </tr>
 
  <tr>
    <td colspan="3"><strong>Comments:</strong>&nbsp;&nbsp;{if is_set($data_array1)} {foreach $data_array1 as $index => $item}<a href="http://collegeyardart.com/index.php/{$item.path}">{$item.name}</a>{$con}{/foreach}{/if} </td>
  </tr>

<tr><td colspan="3"></td></tr>
</table>

 
 