<?php
// if true ALL users must have 2-steps active
$rcmail_config['force_enrollment_users'] = false;

// whitelist, CIDR format available
// NOTE: we need to use .0 IP to define LAN because the class CIDR have a issue about that (we can't use 129.168.1.2/24, for example)
$rcmail_config['whitelist'] = array('192.168.1.0/24', '::1', '192.168.0.9');
