# Overview

A role to install the Baïkal CalDAV (calendars) and CardDAV (contacts) server. CardDAV and CalDAV can be used to sync your contacts and calendars between your different devices: your phone and computer for example.

Baïkal is a PHP application running in Apache and accessible via `dav.example.com`. There is a web UI to add users and calendars but it's intended to be uses by client software on your phone or computer.

The connection is encrypted via the Let's Encrypt support Sovereign already provides.

NOTE: The ownCloud role already adds support for CalDAV and CardDAV so you **don't** need to install Baïkal if you are using ownCloud. This role is meant for those who prefer a more lightweight alternative.

# Install

The role installs the version defined by the `baikal_version` variable and the source is downloaded from the github repository.

It depends on PHP 5, MySQL (might move to PostgreSQL) and Apache. It's a webapp so there is no daemon to start or to watch, it's there when Apache runs.

# Upgrade

It's unknown how upgrades will be handled.  The best case is that an update can be installed over the current version, and code will automatically update the database the first time it connects.
