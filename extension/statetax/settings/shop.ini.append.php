<?php /* #?ini charset="utf-8"?

[VATSettings]
# These two settings will point eZ Publish to look for the handler at extension/jc/vathandlers/statebasedvathandlers.php
Handler=statebased
ExtensionDirectories[]=statetax
# This would have needed to be an attribute of the Country datatype, so we won't use it
#UserCountryAttribute=state
RequireUserCountry=false
# This will be used in the Administration Interface when editing a product
DynamicVatTypeName=State-based Tax
 
[VATMappings]
# Used by StateBasedVATHandler
# Identifier of the state attribute in the User class
StateAttribute=state
StateAttribute1=sstate
DefaultVAT=0
# Mapping from province and territory to tax percentage

StateVATMapItems[]
StateVATMapItems[Oklahoma]=10
StateVATMapItems[oklahoma]=10
StateVATMapItems[OK]=10
*/ ?>
