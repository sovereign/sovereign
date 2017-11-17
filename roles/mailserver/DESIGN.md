# Design Description for Mailserver Role

## Overview

Postfix is the MTA, Dovecot the LDA and IMAP server, and Rspamd is the
only milter used.

Mail delivery looks like this:

  Remote MTA -> Rspamd (milter) -> Postfix -> Rspamd (rspamc) -> Dovecot -> user mailbox

Mail from the remote MTA is received by Postfix and run through
Rspamd.  Greylisting and rejects happen in this pipeline.  Once
Postfix receives the message, it is sent to Dovecot over LMTP.
Dovecot uses the antispam module to run rspamc (employing Rspamd).
The sieve module is finally used to process headers added by Rspamd or
any other milters.

## Mail filters

The only mail filter (milter) used is [Rspamd](https://rspamd.com),
which runs on port 11332.  Rspamd is hooked into postfix with the
`smtpd_milters` variable.  See `etc_postfix_main.cf`.

## Debugging

### Full-text search with Solr

The configuration file `90-plugin.conf` hooks dovecot to use Solr for
full-text search.  It appears in fact that full-text search is only
the subject and not the body, but this needs verified.

To debug, add the keyword `debug` to the options list in the variable
`fts_solr` in `90-plugin.conf`.  Options in this variable are
separated by spaces.  Review `/var/log/mail.log` while searching to
see the URLs used to query Solr.  You can repeat these searches from
your local web browser by using ssh to port forward (i.e., -L
8080:127.0.0.1:8080).  Also, the url `http://127.0.0.1:8080/solr` will
get you to a complicated admin page but is another way to avenue for
verifying that solr is working.

### Rspamd

A few tips:

- Rspam's console listens on `127.0.0.1:11334`.  As above, you can use
  ssh to port forward (e.g., -L 8080:localhost:11334).  The password is `d1`.
- Use `rspamadm` to look at the configuration.
- Use `rspamc` or the web-based console to scan problematic messages
  and see how rspamd scores them.

### DMARC

For verifying DMARC operation, read the rpsamd log in
`/var/log/rspamd` to verify the report generator is running.

For receiving reports, you will get an email if a message comes from
your server that fails authentication (although by configuring
`p=none`, any such email should not be rejected by the other
server).
