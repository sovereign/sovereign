# Overview

This role installs the Roundcube package to enable browser-based email handling. This is useful if you need to access email with something other than your own computer or smart phone.

Roundcube is stable and continues to be actively developed.

# Installing from source

We install from the source package released by the Roundcube team. We used to install Roundcube from apt packages, but the packages for Debian 7 have always been brittle.  Furthermore, the package was dropped in Debian 8 due to lack of time from the package maintainers. The Roundcube project does not release packages, so I don't believe they will design their install and upgrade procedures to enable packaging (in contrast to, for example, the ownCloud team).

# Install and upgrade philosophy

Roundcube is a database-backed package. This means DB upgrades must be considered when new versions of the package are installed by Sovereign.

Finalization of an install is handled manually. This anticipates upgrades, where Roundcube appears to be moving to a model where browsing to an install page triggers either the initial database setup or upgrade of an existing database.

Initial database setup could be scripted with some work. There is a SQL script to initialize the database, but the script does not check if the database is already initialized and fails if run twice. This breaks idempotency. It could be fixed by writing a script to check if the database is already initialized.

It's not clear how robust Roundcube's story is for upgrades. We'll get our first look at this when the role is updated for Version 1.2. Automatic scripting of the initial install should be revisited after some experience with upgrades.  For example, does roundcube go in maintenance mode to prevent code from one version mucking with a database from a prior version? If so, it would be reasonably safe just to rerun this module to push a new version and then let the user finalize the upgrade similar to how ownCloud is done.

# Additional notes

There is a system table of key/value pairs in the database. This could be checked to see if the database already exists.  Version information appears to be in the table also. This would make scripting the initial install pretty easy.

We use sqlite3 for the back end, since not much data is stored in the database.  The database is kept in /decrypted so that it is backed up by tarnsap if that role is deployed.
