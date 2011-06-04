<?php
/**
 * Oauth/call server view
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

ext_activate( 'ezpaypal', 'classes/ezpaypalchecker.php' );
ext_activate( 'ezpaypal', 'classes/speedhorsesubscription.php' );

    
    
				
					
					$s = new SpeedHorseSubscription();
					$userID=556;
				// Fetch Current ObjectID / UserID
				$currentUser = eZUser::fetch($userID);
				
				
				$currentUserObjectID = $currentUser->ContentObjectID;
		
				// Fetch Current User Content Object NodeID and Object
				$currentUserNodeID = eZContentObjectTreeNode::findMainNode( $currentUserObjectID );
				
				$currentUserObject = eZContentObjectTreeNode::fetch( $currentUserNodeID );
		
				// print_r( $s->fetchOrder( $currentUserObjectID, false ) );
				// $currentUserEmail = $currentUser->Email;
				// $order = $s->fetchOrder( $currentUserObjectID, true );
				// if ( is_object( $order ) )
		
				if ( is_numeric( $s->fetchOrder( $currentUserObjectID, false ) ) )
				{
					
					// Fetch Current Order ID
					// $orderID = $s->fetchOrderObject( $currentUserEmail, alse );
					$orderID = $s->fetchOrder( $currentUserObjectID, false );
					//print "Test in if"."<br />";
					//print_r($currentUserObject);
					// Subscription Activation
					//$results = $s->activate( $orderID, $currentUserObject );
					//$ret = $results;
					// print_r( $ret );
					// die();
					updateQuantities($orderID);
					
					
					
					
				}
				else
				{
					// print_r( "Fatal Error: Could not fetch current order"); die();
				}
				  
		
		
		
function updateQuantities($orderID)
	{
		$ret = false;

        // Settings
      	$order = eZOrder::fetch( $orderID );
		
		$productCollection = $order->productCollection();
		$orderedItems = $productCollection->itemList();
		foreach ($orderedItems as $item)
		{
			$contentObject = $item->contentObject();
			$contentObjectVersion =& $contentObject->version($contentObject->attribute('current_version'));
			$contentObjectAttributes =& $contentObjectVersion->contentObjectAttributes();
	
			// iterate over the attributes
			foreach (array_keys($contentObjectAttributes) as $key)
			{
				$contentObjectAttribute =& $contentObjectAttributes[$key];
				$contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
				$attributeIdentifier = $contentClassAttribute->attribute("identifier");
	
				if ($attributeIdentifier == "product_quantity")
				{
					if($contentObjectAttribute->attribute("value")>0){
					
						$newQuantity = $contentObjectAttribute->attribute("value") - $item->ItemCount;
		
						$contentObjectAttribute->fromString($newQuantity);
						$contentObjectAttribute->store();
					}
				}
			}
		}
	}				

?>
