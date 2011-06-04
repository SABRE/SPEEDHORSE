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

    
    $checker =& new eZPaypalChecker( 'paypal.ini' );
	$startStatusCode=3;
    if( $checker->createDataFromPOST() )
    {
      unset ($_POST);                     
      if( $checker->requestValidation() && $checker->checkPaymentStatus() )
      {
          //$orderID = $checker->getFieldValue( 'custom' );
		  $Custom = $checker->getFieldValue( 'custom' );
		  $arr = array();
		  $arr= explode(":",$Custom);
		  $orderID=$arr[0];
		  $userID= $arr[1];
		  
          if( $checker->setupOrderAndPaymentObject( $orderID ) )
          {
              $amount   = $checker->getFieldValue( 'mc_gross' );
              $currency = $checker->getFieldValue( 'mc_currency' );
              if( $checker->checkAmount( $amount ) && $checker->checkCurrency( $currency ) )
              {
                  $checker->approvePayment();
				   $order = &eZOrder::fetch( $orderID );
				  $order->modifyStatus($startStatusCode);
				   //$userID = eZUser::currentUserID();
				   
				  // For subscription product
				  
				// Create a new object
				$s = new SpeedHorseSubscription();
		
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
		
					// Subscription Activation
					$results = $s->activate( $orderID, $currentUserObject );
					$ret = $results;
					// print_r( $ret );
					// die();
				}
				else
				{
					// print_r( "Fatal Error: Could not fetch current order"); die();
				}
		
				// Workflow, Process parameters
				// $parameters = $process->attribute( 'parameter_list' );
				// $orderID = $parameters['order_id'];
				// print_r( $process->attribute( 'parameter_list' ) );
				// die( $ret );
		
				
				  
				  // For subscription product
				  
				  
				  
				  
				  
				  
              }
          }
      }
    }

?>
