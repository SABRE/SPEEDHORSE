<?php /* #?ini charset="utf-8"?

[InfoSettings]
# Matches class id or identifier to information collection type
TypeList[contact_form ]=contact_form

[EmailSettings]
# Matches class id or identifier to information collection type
# SendEmailList[poll]=disabled
SendEmailList[contact_form]=enabled

[DisplaySettings]
# result  - Display IC result, for instance poll result or your form data
# redirect - Redirect to a specific url
# node    - Redirect back to content node
# Matches class id or identifier to information collection type
#DisplayList[poll]=result
DisplayList[contact_form]=result

[CollectionSettings]
# if enabled then information from anonymous users can be collected
# CollectAnonymousData=enabled
# Same as CollectAnonymousData but is a list of IC types and
# their override settings, if specified it will override default setting
CollectAnonymousDataList[contact_form]=enabled

# How information collection is handled in terms of user identification
#
# multiple - each user can submit multiple data
# unique   - one set of data per user, if already exists give a warning
# overwrite - one set of data per user but new entry overwrites old one
CollectionUserData=multiple
# Matches class id or identifier to information collection type
#CollectionUserDataList[feedback]=multiple
CollectionUserDataList[contact_form]=multiple

*/ ?>