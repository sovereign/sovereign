# Overview

This role installs the Roundcube package to enable browser-based email handling. This is useful if you need to access email with something other than your own computer or smart phone.  It is also the only friendly way in Sovereign to edit sieve filters for email.

Roundcube is stable and continues to be actively developed.

# Install

The role installs roundcube from the source package released by the Roundcube team.  The version is pinned.  Old versions of this role installed Roundcube from apt packages, but the packages for Debian 8 do not install unattended correctly unless mysql is used at the backend.  We want to use only one database server (postgres) to save on RAM, so using packages is not an option for now.

Roundcube is installed with sqlite3 for its persistence layer.  This eliminates dependency on a database server and likely improves performance given how little persistet data Roundcube keeps.  Roundcube automatically looks for the database file and intializes it if it is missing.  The file is kept on `/decrypted` since it contains user data, and the database will be backed up automatically if the tarsnap role is used.

PHP composer is used for downloading and installing plugins.  Configuration files are kept with sovereign.  The configuration files for `twofactor_gauthentication` and `carddav` are not modified from their defaults.  I chose to do this so that maintainers could recognize when configuration files change in future plugin versions and decide whether or not to change new defaults.

# Upgrade

It's unknown how upgrades will be handled.  The best case is that an update can be installed over the current version, and code will automatically update the database the first time it connects.  This needs to be considered for plugins that store data also.
