# Design Description for Common Role

## Let's Encrypt Support

### Design approach

The Let's Encrypt service uses DNS to look up domains being registered and then contact the client to verify. For this to work, DNS records must be configured before the playbook is run the first time.

A single certificate is created using Let's Encrypt with SANs used for the subdomains. The user must configure the list of subdomains to register in `vars/user.yml` unless they are installing all services, i.e., the default list of subdomains is everything.

Certificate renewal is done automatically using cron.

Certificates and private keys are backed up using tarsnap.

The role is designed to fail if a certificate cannot be generated for all subdomains listed.  This catches DNS configuration errors where a subdomain should have a record but does not.  Errors in the other direction (not all subdomains are listed in the SANs) can be addressed after detection by fixing the configuration and rerunning the role.

### Alternative approaches

Two other approaches were considered.

#### One certificate per domain

Another way to generate certificates is to generate one certificate per domain and expect each module that uses a subdomain to generate its own certificate for the subdomain.

This was prototyped. The common role included a parameterized task list that could be invoked by modules that needed to generate a key. The certificate renewal script run by cron could be modified to update all the certificates in the `live` directory.

This approach was rejected due to complexity. This would have been the first time modules needed to invoke a task list from another module. Managing multiple certificates is also more complicated.

#### Not requiring the user to list subdomains in vars/user.yml

It would be desirable if the user did not have to list the subdomains used in `vars/user.yml`. This introduces an opportunity for the subdomain list to not match what is configured in DNS and the list of roles. Three pieces of information have to be coordinated instead of two.

One approach to avoiding the subdomains variable looks like this.

1. Install a wildcard certificate in the Lets Encrypt folder when the common role runs.  This would be sufficient for services to get off the ground as the playbook is executed.
2. As modules run, they register subdomains they need in a variable.
3. The last module run is a `letsencrypt` module that uses the registration variable to generate the real certificate with SANs and then bounce services using the same script cron will need to bounce services on certificate renewal.

The whole thing becomes a bootstrapping process.

This approach was not prototyped. It was rejected since the `letsencrypt` role would not be idempotent. Furthermore, in order to run at all, the entire playbook would need to be run.

Asking the user to list subdomains in `vars/user.yml` is probably ok.  The user must preconfigure their DNS records before running the playbook so that Let's Encrypt can verify domains.  Therefore, the user is going to know what subdomains they are using before the first run of the playbook.
