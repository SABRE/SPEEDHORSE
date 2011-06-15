<?php
class statebasedVATHandler
{
    function getVatPercent( $object, $country )
    {
        // We use neither the product object nor the country to determine VAT in this case
        // Instead, we determine VAT based on a specified user attribute for the state
        $shopINI = eZINI::instance( 'shop.ini.append.php' );
		$http = eZHTTPTool::instance();
        $stateVATMapItems = $shopINI->variable( 'VATMappings', 'StateVATMapItems' );
        $stateAttribute = $shopINI->variable( 'VATMappings', 'StateAttribute' );
		$stateAttribute1 = $shopINI->variable( 'VATMappings', 'StateAttribute1' );
		//print $stateAttribute;
        // Default VAT should be your home state
        $defaultVAT = $shopINI->variable( 'VATMappings', 'DefaultVAT' );
        $percentage = $defaultVAT;
 
        // Get the value of the user's state and subsequently the VAT for that state
        $currentUser = eZUser::currentUser();
        $currentUserObject = $currentUser->contentObject();
        $dataMap = $currentUserObject->attribute( 'data_map' );
		foreach( $dataMap as $key => $value ) //looping through each field
		{
			 //print( "$key: $value->attribute( 'data_text' )" );
			 if($key==$stateAttribute){
			 	$state=$value->attribute( 'data_text' );
			 }
			 
		}	 
			 
			 	//if($state=="")	{
					$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );

					$order = eZOrder::fetch( $orderID );
					//print "<br />";
					//print_r($order);
					if($order){
						$xmlDoc =& $order->attribute( 'data_text_1' );
						if( $xmlDoc != null )
						{
				
							$domDocument = new DOMDocument( '1.0', 'utf-8' );
							$success = $domDocument->loadXML( $xmlDoc );
							if ( $success )
							{
								$root = $domDocument->documentElement;
								$state = $root->getElementsByTagName( 'state' )->item( 0 )->textContent;
								
							}
							
						}		
			 
					 }
					 
					// print "cccc". $state;
			//}
			
		
		
		
 		//print "<br />test".$dataMap[ $stateAttribute ]."<br />";
        // Some logic to get at the state / province name in an ezselection attribute
        //if( isset( $state ) )//&& 'ezselection' == $dataMap[ $stateAttribute ]->attribute( 'data_type_string' ) )
       // {
            //$selectionClassContent = $dataMap[ $stateAttribute ]->attribute( 'class_content' );
           // $selectionOptions = $selectionClassContent[ 'options' ];
           // $selectionID = $dataMap[ $stateAttribute ]->attribute( 'data_text' );
           // $state = $selectionOptions[ $selectionID ][ 'name' ];
		   
		   	//	$state=$dataMap[ $stateAttribute ];
				//print "kjghjgk".$state;
				
				//exit;
				//$percentage = $stateVATMapItems[ $state ]
       // }
	   
	   //print "<br />State=".$state;
	   
        if ( isset( $stateVATMapItems[ $state ] ) )
        {
            $percentage = $stateVATMapItems[ $state ];
        }
        return $percentage;
    }
}
?>