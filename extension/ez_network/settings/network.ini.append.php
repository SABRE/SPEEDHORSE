<?php /*

[NetworkSettings]
ExtensionPath=ez_network

# PHP CLI monitor
PHPCLI=php

# Local IP
LocalIP=

# PHP free space and total space tests
DriveList[]
DriveList[]=/

# Used for oauth (needs to end with slash)
Server=http://support.ez.no/

[eZPHPFileSettings]
# Sets how often monitor file change monitors should be run.
MonitorFileChangeFrequency=1

# Files to include in PHP checker.
MonitorIncludePath[]
MonitorIncludePath[]=index.php
MonitorIncludePath[]=pre_check.php
MonitorIncludePath[]=access.php
MonitorIncludePath[]=webdav.php
MonitorIncludePath[]=soap.php
MonitorIncludePath[]=kernel
MonitorIncludePath[]=lib
MonitorIncludePath[]=cronjobs
MonitorIncludePath[]=bin
MonitorIncludePath[]=update/common

[ClusterSettings]
# Cluster node type
Mode=master

#Optional node ID. Used for cluster installations.
NodeID=


*/ ?>
