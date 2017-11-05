# Design Description for Mailserver Role

## Mail filters

Four mail filters are used:

* [Postgrey](http://postgrey.schweikert.ch/), which runes on port 10023 and is hooked into postfix with the `smtpd_recipient_restrictions` variable;
* [OpenDKIM](http://www.opendkim.org/), which runs on port 8891;
* [OpenDMARC](http://www.trusteddomain.org/opendmarc/), which runs on port 54321; and
* [Rspamd](https://rspamd.com), which runs on port 11332.

OpenDKIM, OpenDMARC, and Rspamd are all hooked into postfix with the `smtpd_milters` variable.  All of this can be found in `etc_postfix_main.cf`.
