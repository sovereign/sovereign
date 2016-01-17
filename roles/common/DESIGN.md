# Design Description for Common Role

## Let's Encrypt Support

[Let's Encrypt](https://letsencrypt.org) (LE) is an automated certificate authority that provides free SSL certificates that are trusted by all major browsers.  LE certificates are used by Sovereign instead of purchased certificates from authorities like RapidSSL in order to reduce the out-of-pocket cost of deploying Sovereign and avoid end-user problems with self-signed certificates.

### Design approach

The Let's Encrypt service uses DNS to look up domains being registered and then contact the client to verify. For this to work, DNS records must be configured before the playbook is run the first time.

A single certificate is created using Let's Encrypt with SANs used for the subdomains.  At deploy-time, a script is used to query DNS for known subdomains, build a list of the subset that is registered, and use it when making the certificate request of Let's Encrypt.

Several packages need access to the private key. Not all are run as root. An example is Prosody (XMPP). Such users are added to the ssl-cert group, and /etc/letsencrypt is set up to allow keys to be read by ssl-cert.

Certificates and private keys are backed up using tarsnap.

Certificate renewal is done automatically using cron. The cron script must be aware of private key copies and update them as well. Services that depend on new keys must also be bounced. It is up to roles that rely on keys to modify the cron script (preferably using `lineinfile` or something similar) to accomplish this.

### Testing support

An isolated VM deployed with Vagrant is used for testing. The Let's Encrypt service cannot be used to get keys for it, since it is not bound with DNS. A self-signed wildcard key is therefore used for testing. The wildcard key, certificate, and chain are installed in the same way that Let's Encrypt keys are installed.

### Alternative approaches

Another way to generate certificates is to generate one certificate per domain and expect each module that uses a subdomain to generate its own certificate for the subdomain.

This was prototyped. The common role included a parameterized task list that could be invoked by modules that needed to generate a key. The certificate renewal script run by cron could be modified to update all the certificates in the `live` directory.

This approach was rejected due to complexity. This would have been the first time modules needed to invoke a task list from another module. Managing multiple certificates is also more complicated.
