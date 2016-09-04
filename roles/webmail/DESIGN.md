# Design Description for Webmail Role

## Scope

The goal of this role is twofold: 1) provide an interface for configuring dovecot sieves, and 2) provide a web-based front-end for email stored on the server for users who want access "anywhere" without a mobile device.

## Approach

The Roundcube package is used for this role.  It's provided in backports on Debian and included in Xenial.  Plugins are used for configuring [sieve filters](http://sieve.info/), optional two-factor authentication for additional security, and CardDAV support.

Roundcube uses a database.  Multiple backends are supported including postgres.  Postgres is used by sovereign.  Configuration is handled using `debconf` to anticipate package updates.

An alternative approach would be to install Roundcube from the main development distribution instead of from packages.  The Roundcube development team does not distribute packages, so this could be preferred to ensure correct (tested) upgrades to future releases of Roundcube.

## Testing with Vagrant

Testing with Vagrant is currently difficult.  If you get a login screen, the database was configured correctly.  If you attempt to login, you will be unable to connect to storage.  This is because, as roundcube is configured, PHP will not make an SSL-based connection to the imap server without validating the certificate.  This fails for self-signed certificates.  It fails when the hostname does not match the CN.

It may be possible to work around this by using connection options (commented out in the roundcube configuration file).  As of Roundcube 1.1.4, though, the configuration parameter name does not match the parameter name used in the imap connection code.  If you are interested in fixing this up, look at the `_connect` method in `rcube-imap-common.php` (or something like that) in the roundcube source.
