<?php /* #?ini charset="utf-8"?

[ServerSettings]
#ServerName=https://www.paypal.com
ServerName=https://www.sandbox.paypal.com
RequestURI=/cgi-bin/webscr

[PaypalSettings]
# max length of description of the order
# everything that exceeds will be truncated 
MaxDescriptionLength=127

# field: "business"
# e-mail of receiver
Business=pralay_1284702979_biz@gmail.com

# field: "no_note" 
# prompt to include a note. 0 - prompt, 1 - dont prompt
#NoNote=0

# field: "cn"
# label that will appear above the note field
# maximum 40 characters
#NoteLabel=Some Label

# field: "page_style"
PageStyle=
#PageStyle=FiveClock
#PageStyle=paypal

# field: "cs" 
# background color if PageStyle not set. 0 - white, 1 - black.                            
#BackgroundColor=0

#filed: "image_url" (URI to the logo image (150 x 50 pixels))
#LogoURI=/var/shop/images/mylogo.png

[SimpleSubscriptionSettings]

# Subscription Group (Premium)
SubscriptionGroupNodeID=360;361;362;363;364;365

# Guest Group (Default)
SubscriptionGuestGroupNodeID=44

# Subscription Product
SubscriptionProductNodeID=337;338;339;340;341;342
SubscriptionProductObjectID=399;400;401;402;403;404
SubscriptionProductClassID=21

# Class Attribute Names
SubscriptionProductAttributeName=subscription_days
SubscriptionUserAttributeName=expire

# User ClassID (Cronjob Dependency)
UserClassID=4

# Admin UserID (Cronjob Dependency)
AdministratorUserID=14

# Send Emails to Users
SendSubscriptionExpirationNotificationEmails=enabled

# Enable Debug (Not recommended)
Debug=enabled

# SubscriptionProductClasses[]
# SubscriptionProductClasses[]=Product

# Expiration Cronjob Log
LogDebug=enabled
Log=var/log/bcsimplesubscription.log
*/ ?>
