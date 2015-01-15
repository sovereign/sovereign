<?php

/*
+-----------------------------------------------------------------------+
| Main configuration file                                               |
|                                                                       |
| This file is part of the Roundcube Webmail client                     |
| Copyright (C) 2005-2011, The Roundcube Dev Team                       |
|                                                                       |
| Licensed under the GNU General Public License version 3 or            |
| any later version with exceptions for skins & plugins.                |
| See the README file for a full license statement.                     |
|                                                                       |
+-----------------------------------------------------------------------+

*/

$rcmail_config = array();

// ----------------------------------
// LOGGING/DEBUGGING
// ----------------------------------

// system error reporting, sum of: 1 = log; 4 = show, 8 = trace
$rcmail_config['debug_level'] = 1;

// log driver:  'syslog' or 'file'.
$rcmail_config['log_driver'] = 'file';

// date format for log entries
// (read http://php.net/manual/en/function.date.php for all format characters)  
$rcmail_config['log_date_format'] = 'd-M-Y H:i:s O';

// Syslog ident string to use, if using the 'syslog' log driver.
$rcmail_config['syslog_id'] = 'roundcube';

// Syslog facility to use, if using the 'syslog' log driver.
// For possible values see installer or http://php.net/manual/en/function.openlog.php
$rcmail_config['syslog_facility'] = LOG_USER;

// Log sent messages to <log_dir>/sendmail or to syslog
$rcmail_config['smtp_log'] = true;

// Log successful logins to <log_dir>/userlogins or to syslog
$rcmail_config['log_logins'] = false;

// Log session authentication errors to <log_dir>/session or to syslog
$rcmail_config['log_session'] = false;

// Log SQL queries to <log_dir>/sql or to syslog
$rcmail_config['sql_debug'] = false;

// Log IMAP conversation to <log_dir>/imap or to syslog
$rcmail_config['imap_debug'] = false;

// Log LDAP conversation to <log_dir>/ldap or to syslog
$rcmail_config['ldap_debug'] = false;

// Log SMTP conversation to <log_dir>/smtp or to syslog
$rcmail_config['smtp_debug'] = false;

// ----------------------------------
// IMAP
// ----------------------------------

// The mail host chosen to perform the log-in.
// Leave blank to show a textbox at login, give a list of hosts
// to display a pulldown menu or set one host as string.
// To use SSL/TLS connection, enter hostname with prefix ssl:// or tls://
// Supported replacement variables:
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %s - domain name after the '@' from e-mail address provided at login screen
// For example %n = mail.domain.tld, %t = domain.tld
// WARNING: After hostname change update of mail_host column in users table is
//          required to match old user data records with the new host.
$rcmail_config['default_host'] = 'ssl://127.0.0.1:993';

// TCP port used for IMAP connections
$rcmail_config['default_port'] = 143;

// IMAP AUTH type (DIGEST-MD5, CRAM-MD5, LOGIN, PLAIN or null to use
// best server supported one)
$rcmail_config['imap_auth_type'] = null;

// If you know your imap's folder delimiter, you can specify it here.
// Otherwise it will be determined automatically
$rcmail_config['imap_delimiter'] = null;

// If IMAP server doesn't support NAMESPACE extension, but you're
// using shared folders or personal root folder is non-empty, you'll need to
// set these options. All can be strings or arrays of strings.
// Folders need to be ended with directory separator, e.g. "INBOX."
// (special directory "~" is an exception to this rule)
// These can be used also to overwrite server's namespaces
$rcmail_config['imap_ns_personal'] = null;
$rcmail_config['imap_ns_other']    = null;
$rcmail_config['imap_ns_shared']   = null;

// By default IMAP capabilities are readed after connection to IMAP server
// In some cases, e.g. when using IMAP proxy, there's a need to refresh the list
// after login. Set to True if you've got this case.
$rcmail_config['imap_force_caps'] = false;

// By default list of subscribed folders is determined using LIST-EXTENDED
// extension if available. Some servers (dovecot 1.x) returns wrong results
// for shared namespaces in this case. http://trac.roundcube.net/ticket/1486225
// Enable this option to force LSUB command usage instead.
$rcmail_config['imap_force_lsub'] = false;

// Some server configurations (e.g. Courier) doesn't list folders in all namespaces
// Enable this option to force listing of folders in all namespaces
$rcmail_config['imap_force_ns'] = false;

// IMAP connection timeout, in seconds. Default: 0 (no limit)
$rcmail_config['imap_timeout'] = 0;

// Optional IMAP authentication identifier to be used as authorization proxy
$rcmail_config['imap_auth_cid'] = null;

// Optional IMAP authentication password to be used for imap_auth_cid
$rcmail_config['imap_auth_pw'] = null;

// Type of IMAP indexes cache. Supported values: 'db', 'apc' and 'memcache'.
$rcmail_config['imap_cache'] = null;

// Enables messages cache. Only 'db' cache is supported.
$rcmail_config['messages_cache'] = false;


// ----------------------------------
// SMTP
// ----------------------------------

// SMTP server host (for sending mails).
// To use SSL/TLS connection, enter hostname with prefix ssl:// or tls://
// If left blank, the PHP mail() function is used
// Supported replacement variables:
// %h - user's IMAP hostname
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
$rcmail_config['smtp_server'] = 'ssl://127.0.0.1';

// SMTP port (default is 25; use 587 for STARTTLS or 465 for the
// deprecated SSL over SMTP (aka SMTPS))
$rcmail_config['smtp_port'] = 465;

// SMTP username (if required) if you use %u as the username Roundcube
// will use the current username for login
$rcmail_config['smtp_user'] = '%u';

// SMTP password (if required) if you use %p as the password Roundcube
// will use the current user's password for login
$rcmail_config['smtp_pass'] = '%p';

// SMTP AUTH type (DIGEST-MD5, CRAM-MD5, LOGIN, PLAIN or empty to use
// best server supported one)
$rcmail_config['smtp_auth_type'] = '';

// Optional SMTP authentication identifier to be used as authorization proxy
$rcmail_config['smtp_auth_cid'] = null;

// Optional SMTP authentication password to be used for smtp_auth_cid
$rcmail_config['smtp_auth_pw'] = null;

// SMTP HELO host 
// Hostname to give to the remote server for SMTP 'HELO' or 'EHLO' messages 
// Leave this blank and you will get the server variable 'server_name' or 
// localhost if that isn't defined. 
$rcmail_config['smtp_helo_host'] = '';

// SMTP connection timeout, in seconds. Default: 0 (no limit)
// Note: There's a known issue where using ssl connection with
// timeout > 0 causes connection errors (https://bugs.php.net/bug.php?id=54511)
$rcmail_config['smtp_timeout'] = 0;

// ----------------------------------
// SYSTEM
// ----------------------------------

// THIS OPTION WILL ALLOW THE INSTALLER TO RUN AND CAN EXPOSE SENSITIVE CONFIG DATA.
// ONLY ENABLE IT IF YOU'RE REALLY SURE WHAT YOU'RE DOING!
$rcmail_config['enable_installer'] = false;

// don't allow these settings to be overriden by the user
$rcmail_config['dont_override'] = array();

// provide an URL where a user can get support for this Roundcube installation
// PLEASE DO NOT LINK TO THE ROUNDCUBE.NET WEBSITE HERE!
$rcmail_config['support_url'] = '';

// replace Roundcube logo with this image
// specify an URL relative to the document root of this Roundcube installation
$rcmail_config['skin_logo'] = null;

// automatically create a new Roundcube user when log-in the first time.
// a new user will be created once the IMAP login succeeds.
// set to false if only registered users can use this service
$rcmail_config['auto_create_user'] = true;

// Enables possibility to log in using email address from user identities
$rcmail_config['user_aliases'] = false;

// use this folder to store log files (must be writeable for apache user)
// This is used by the 'file' log driver.
$rcmail_config['log_dir'] = 'logs/';

// use this folder to store temp files (must be writeable for apache user)
$rcmail_config['temp_dir'] = 'temp/';

// lifetime of message cache
// possible units: s, m, h, d, w
$rcmail_config['message_cache_lifetime'] = '10d';

// enforce connections over https
// with this option enabled, all non-secure connections will be redirected.
// set the port for the ssl connection as value of this option if it differs from the default 443
$rcmail_config['force_https'] = true;

// tell PHP that it should work as under secure connection
// even if it doesn't recognize it as secure ($_SERVER['HTTPS'] is not set)
// e.g. when you're running Roundcube behind a https proxy
// this option is mutually exclusive to 'force_https' and only either one of them should be set to true.
$rcmail_config['use_https'] = false;

// Allow browser-autocompletion on login form.
// 0 - disabled, 1 - username and host only, 2 - username, host, password
$rcmail_config['login_autocomplete'] = 0;

// Forces conversion of logins to lower case.
// 0 - disabled, 1 - only domain part, 2 - domain and local part.
// If users authentication is case-insensitive this must be enabled.
// Note: After enabling it all user records need to be updated, e.g. with query:
//       UPDATE users SET username = LOWER(username);
$rcmail_config['login_lc'] = 2;

// Includes should be interpreted as PHP files
$rcmail_config['skin_include_php'] = false;

// display software version on login screen
$rcmail_config['display_version'] = false;

// Session lifetime in minutes
$rcmail_config['session_lifetime'] = 10;

// Session domain: .example.org
$rcmail_config['session_domain'] = '';

// Session name. Default: 'roundcube_sessid'
$rcmail_config['session_name'] = null;

// Session authentication cookie name. Default: 'roundcube_sessauth'
$rcmail_config['session_auth_name'] = null;

// Session path. Defaults to PHP session.cookie_path setting.
$rcmail_config['session_path'] = null;

// Backend to use for session storage. Can either be 'db' (default) or 'memcache'
// If set to memcache, a list of servers need to be specified in 'memcache_hosts'
// Make sure the Memcache extension (http://pecl.php.net/package/memcache) version >= 2.0.0 is installed
$rcmail_config['session_storage'] = 'db';

// Use these hosts for accessing memcached
// Define any number of hosts in the form of hostname:port or unix:///path/to/socket.file
$rcmail_config['memcache_hosts'] = null; // e.g. array( 'localhost:11211', '192.168.1.12:11211', 'unix:///var/tmp/memcached.sock' );

// check client IP in session athorization
$rcmail_config['ip_check'] = false;

// check referer of incoming requests
$rcmail_config['referer_check'] = false;

// X-Frame-Options HTTP header value sent to prevent from Clickjacking.
// Possible values: sameorigin|deny. Set to false in order to disable sending them
$rcmail_config['x_frame_options'] = 'sameorigin';

// this key is used to encrypt the users imap password which is stored
// in the session record (and the client cookie if remember password is enabled).
// please provide a string of exactly 24 chars.
$rcmail_config['des_key'] = 'cQro25fVv3ruWTNh0a6Sm1Rp';

// Automatically add this domain to user names for login
// Only for IMAP servers that require full e-mail addresses for login
// Specify an array with 'host' => 'domain' values to support multiple hosts
// Supported replacement variables:
// %h - user's IMAP hostname
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
$rcmail_config['username_domain'] = '';

// This domain will be used to form e-mail addresses of new users
// Specify an array with 'host' => 'domain' values to support multiple hosts
// Supported replacement variables:
// %h - user's IMAP hostname
// %n - http hostname ($_SERVER['SERVER_NAME'])
// %d - domain (http hostname without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
$rcmail_config['mail_domain'] = '';

// Password charset.
// Use it if your authentication backend doesn't support UTF-8.
// Defaults to ISO-8859-1 for backward compatibility
$rcmail_config['password_charset'] = 'ISO-8859-1';

// How many seconds must pass between emails sent by a user
$rcmail_config['sendmail_delay'] = 0;

// Maximum number of recipients per message. Default: 0 (no limit)
$rcmail_config['max_recipients'] = 0; 

// Maximum allowednumber of members of an address group. Default: 0 (no limit)
// If 'max_recipients' is set this value should be less or equal
$rcmail_config['max_group_members'] = 0; 

// add this user-agent to message headers when sending
$rcmail_config['useragent'] = 'Roundcube Webmail/'.RCMAIL_VERSION;

// use this name to compose page titles
$rcmail_config['product_name'] = 'Roundcube Webmail';

// try to load host-specific configuration
// see http://trac.roundcube.net/wiki/Howto_Config for more details
$rcmail_config['include_host_config'] = false;

// path to a text file which will be added to each sent message
// paths are relative to the Roundcube root folder
$rcmail_config['generic_message_footer'] = '';

// path to a text file which will be added to each sent HTML message
// paths are relative to the Roundcube root folder
$rcmail_config['generic_message_footer_html'] = '';

// add a received header to outgoing mails containing the creators IP and hostname
$rcmail_config['http_received_header'] = false;

// Whether or not to encrypt the IP address and the host name
// these could, in some circles, be considered as sensitive information;
// however, for the administrator, these could be invaluable help
// when tracking down issues.
$rcmail_config['http_received_header_encrypt'] = false;

// This string is used as a delimiter for message headers when sending
// a message via mail() function. Leave empty for auto-detection
$rcmail_config['mail_header_delimiter'] = NULL;

// number of chars allowed for line when wrapping text.
// text wrapping is done when composing/sending messages
$rcmail_config['line_length'] = 72;

// send plaintext messages as format=flowed
$rcmail_config['send_format_flowed'] = true;

// According to RFC2298, return receipt envelope sender address must be empty.
// If this option is true, Roundcube will use user's identity as envelope sender for MDN responses.
$rcmail_config['mdn_use_from'] = false;

// Set identities access level:
// 0 - many identities with possibility to edit all params
// 1 - many identities with possibility to edit all params but not email address
// 2 - one identity with possibility to edit all params
// 3 - one identity with possibility to edit all params but not email address
// 4 - one identity with possibility to edit only signature
$rcmail_config['identities_level'] = 0;

// Mimetypes supported by the browser.
// attachments of these types will open in a preview window
// either a comma-separated list or an array: 'text/plain,text/html,text/xml,image/jpeg,image/gif,image/png,application/pdf'
$rcmail_config['client_mimetypes'] = null;  # null == default

// Path to a local mime magic database file for PHPs finfo extension.
// Set to null if the default path should be used.
$rcmail_config['mime_magic'] = null;

// Absolute path to a local mime.types mapping table file.
// This is used to derive mime-types from the filename extension or vice versa.
// Such a file is usually part of the apache webserver. If you don't find a file named mime.types on your system,
// download it from http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
$rcmail_config['mime_types'] = null;

// path to imagemagick identify binary
$rcmail_config['im_identify_path'] = null;

// path to imagemagick convert binary
$rcmail_config['im_convert_path'] = null;

// Size of thumbnails from image attachments displayed below the message content.
// Note: whether images are displayed at all depends on the 'inline_images' option.
// Set to 0 to display images in full size.
$rcmail_config['image_thumbnail_size'] = 240;

// maximum size of uploaded contact photos in pixel
$rcmail_config['contact_photo_size'] = 160;

// Enable DNS checking for e-mail address validation
$rcmail_config['email_dns_check'] = false;

// Disables saving sent messages in Sent folder (like gmail) (Default: false)
// Note: useful when SMTP server stores sent mail in user mailbox
$rcmail_config['no_save_sent_messages'] = false;

// ----------------------------------
// PLUGINS
// ----------------------------------

// List of active plugins (in plugins/ directory)
$rcmail_config['plugins'] = array('managesieve', 'carddav', 'twofactor_gauthenticator');

// ----------------------------------
// USER INTERFACE
// ----------------------------------

// default messages sort column. Use empty value for default server's sorting, 
// or 'arrival', 'date', 'subject', 'from', 'to', 'fromto', 'size', 'cc'
$rcmail_config['message_sort_col'] = '';

// default messages sort order
$rcmail_config['message_sort_order'] = 'DESC';

// These cols are shown in the message list. Available cols are:
// subject, from, to, fromto, cc, replyto, date, size, status, flag, attachment, 'priority'
$rcmail_config['list_cols'] = array('subject', 'status', 'fromto', 'date', 'size', 'flag', 'attachment');

// the default locale setting (leave empty for auto-detection)
// RFC1766 formatted language name like en_US, de_DE, de_CH, fr_FR, pt_BR
$rcmail_config['language'] = 'en_US';

// use this format for date display (date or strftime format)
$rcmail_config['date_format'] = 'Y-m-d';

// give this choice of date formats to the user to select from
// Note: do not use ambiguous formats like m/d/Y
$rcmail_config['date_formats'] = array('Y-m-d', 'Y/m/d', 'Y.m.d', 'd-m-Y', 'd/m/Y', 'd.m.Y', 'j.n.Y');

// use this format for time display (date or strftime format)
$rcmail_config['time_format'] = 'H:i';

// give this choice of time formats to the user to select from
$rcmail_config['time_formats'] = array('G:i', 'H:i', 'g:i a', 'h:i A');

// use this format for short date display (derived from date_format and time_format)
$rcmail_config['date_short'] = 'D H:i';

// use this format for detailed date/time formatting (derived from date_format and time_format)
$rcmail_config['date_long'] = 'Y-m-d H:i';

// store draft message is this mailbox
// leave blank if draft messages should not be stored
// NOTE: Use folder names with namespace prefix (INBOX. on Courier-IMAP)
$rcmail_config['drafts_mbox'] = 'Drafts';

// store spam messages in this mailbox
// NOTE: Use folder names with namespace prefix (INBOX. on Courier-IMAP)
$rcmail_config['junk_mbox'] = 'Junk';

// store sent message is this mailbox
// leave blank if sent messages should not be stored
// NOTE: Use folder names with namespace prefix (INBOX. on Courier-IMAP)
$rcmail_config['sent_mbox'] = 'Sent';

// move messages to this folder when deleting them
// leave blank if they should be deleted directly
// NOTE: Use folder names with namespace prefix (INBOX. on Courier-IMAP)
$rcmail_config['trash_mbox'] = 'Trash';

// display these folders separately in the mailbox list.
// these folders will also be displayed with localized names
// NOTE: Use folder names with namespace prefix (INBOX. on Courier-IMAP)
$rcmail_config['default_folders'] = array('INBOX', 'Drafts', 'Sent', 'Junk', 'Trash');

// automatically create the above listed default folders on first login
$rcmail_config['create_default_folders'] = true;

// protect the default folders from renames, deletes, and subscription changes
$rcmail_config['protect_default_folders'] = true;

// if in your system 0 quota means no limit set this option to true 
$rcmail_config['quota_zero_as_unlimited'] = false;

// Make use of the built-in spell checker. It is based on GoogieSpell.
// Since Google only accepts connections over https your PHP installatation
// requires to be compiled with Open SSL support
$rcmail_config['enable_spellcheck'] = true;

// Enables spellchecker exceptions dictionary.
// Setting it to 'shared' will make the dictionary shared by all users.
$rcmail_config['spellcheck_dictionary'] = false;

// Set the spell checking engine. 'googie' is the default. 'pspell' is also available,
// but requires the Pspell extensions. When using Nox Spell Server, also set 'googie' here.
$rcmail_config['spellcheck_engine'] = 'pspell';

// For a locally installed Nox Spell Server, please specify the URI to call it.
// Get Nox Spell Server from http://orangoo.com/labs/?page_id=72
// Leave empty to use the Google spell checking service, what means
// that the message content will be sent to Google in order to check spelling
$rcmail_config['spellcheck_uri'] = '';

// These languages can be selected for spell checking.
// Configure as a PHP style hash array: array('en'=>'English', 'de'=>'Deutsch');
// Leave empty for default set of available language.
$rcmail_config['spellcheck_languages'] = NULL;

// Makes that words with all letters capitalized will be ignored (e.g. GOOGLE)
$rcmail_config['spellcheck_ignore_caps'] = false;

// Makes that words with numbers will be ignored (e.g. g00gle)
$rcmail_config['spellcheck_ignore_nums'] = false;

// Makes that words with symbols will be ignored (e.g. g@@gle)
$rcmail_config['spellcheck_ignore_syms'] = false;

// Use this char/string to separate recipients when composing a new message
$rcmail_config['recipients_separator'] = ',';

// don't let users set pagesize to more than this value if set
$rcmail_config['max_pagesize'] = 200;

// Minimal value of user's 'refresh_interval' setting (in seconds)
$rcmail_config['min_refresh_interval'] = 60;

// Enables files upload indicator. Requires APC installed and enabled apc.rfc1867 option.
// By default refresh time is set to 1 second. You can set this value to true
// or any integer value indicating number of seconds.
$rcmail_config['upload_progress'] = false;

// Specifies for how many seconds the Undo button will be available
// after object delete action. Currently used with supporting address book sources.
// Setting it to 0, disables the feature.
$rcmail_config['undo_timeout'] = 0;

// ----------------------------------
// ADDRESSBOOK SETTINGS
// ----------------------------------

// This indicates which type of address book to use. Possible choises:
// 'sql' (default), 'ldap' and ''.
// If set to 'ldap' then it will look at using the first writable LDAP
// address book as the primary address book and it will not display the
// SQL address book in the 'Address Book' view.
// If set to '' then no address book will be displayed or only the
// addressbook which is created by a plugin (like CardDAV).
$rcmail_config['address_book_type'] = 'sql';

// In order to enable public ldap search, configure an array like the Verisign
// example further below. if you would like to test, simply uncomment the example.
// Array key must contain only safe characters, ie. a-zA-Z0-9_
$rcmail_config['ldap_public'] = array();

// If you are going to use LDAP for individual address books, you will need to 
// set 'user_specific' to true and use the variables to generate the appropriate DNs to access it.
//
// The recommended directory structure for LDAP is to store all the address book entries
// under the users main entry, e.g.:
//
//  o=root
//   ou=people
//    uid=user@domain
//  mail=contact@contactdomain
//
// So the base_dn would be uid=%fu,ou=people,o=root
// The bind_dn would be the same as based_dn or some super user login.
/*
* example config for Verisign directory
*
$rcmail_config['ldap_public']['Verisign'] = array(
'name'          => 'Verisign.com',
// Replacement variables supported in host names:
// %h - user's IMAP hostname
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
'hosts'         => array('directory.verisign.com'),
'port'          => 389,
'use_tls'	      => false,
'ldap_version'  => 3,       // using LDAPv3
'network_timeout' => 10,    // The timeout (in seconds) for connect + bind arrempts. This is only supported in PHP >= 5.3.0 with OpenLDAP 2.x
'user_specific' => false,   // If true the base_dn, bind_dn and bind_pass default to the user's IMAP login.
// %fu - The full username provided, assumes the username is an email
//       address, uses the username_domain value if not an email address.
// %u  - The username prior to the '@'.
// %d  - The domain name after the '@'.
// %dc - The domain name hierarchal string e.g. "dc=test,dc=domain,dc=com"
// %dn - DN found by ldap search when search_filter/search_base_dn are used
'base_dn'       => '',
'bind_dn'       => '',
'bind_pass'     => '',
// It's possible to bind for an individual address book
// The login name is used to search for the DN to bind with
'search_base_dn' => '',
'search_filter'  => '',   // e.g. '(&(objectClass=posixAccount)(uid=%u))'
// DN and password to bind as before searching for bind DN, if anonymous search is not allowed
'search_bind_dn' => '',
'search_bind_pw' => '',
// Default for %dn variable if search doesn't return DN value
'search_dn_default' => '',
// Optional authentication identifier to be used as SASL authorization proxy
// bind_dn need to be empty
'auth_cid'       => '',
// SASL authentication method (for proxy auth), e.g. DIGEST-MD5
'auth_method'    => '',
// Indicates if the addressbook shall be hidden from the list.
// With this option enabled you can still search/view contacts.
'hidden'        => false,
// Indicates if the addressbook shall not list contacts but only allows searching.
'searchonly'    => false,
// Indicates if we can write to the LDAP directory or not.
// If writable is true then these fields need to be populated:
// LDAP_Object_Classes, required_fields, LDAP_rdn
'writable'       => false,
// To create a new contact these are the object classes to specify
// (or any other classes you wish to use).
'LDAP_Object_Classes' => array('top', 'inetOrgPerson'),
// The RDN field that is used for new entries, this field needs
// to be one of the search_fields, the base of base_dn is appended
// to the RDN to insert into the LDAP directory.
'LDAP_rdn'       => 'cn',
// The required fields needed to build a new contact as required by
// the object classes (can include additional fields not required by the object classes).
'required_fields' => array('cn', 'sn', 'mail'),
'search_fields'   => array('mail', 'cn'),  // fields to search in
// mapping of contact fields to directory attributes
//   for every attribute one can specify the number of values (limit) allowed.
//   default is 1, a wildcard * means unlimited
'fieldmap' => array(
// Roundcube  => LDAP:limit
'name'        => 'cn',
'surname'     => 'sn',
'firstname'   => 'givenName',
'jobtitle'    => 'title',
'email'       => 'mail:*',
'phone:home'  => 'homePhone',
'phone:work'  => 'telephoneNumber',
'phone:mobile' => 'mobile',
'phone:pager' => 'pager',
'street'      => 'street',
'zipcode'     => 'postalCode',
'region'      => 'st',
'locality'    => 'l',
// if you country is a complex object, you need to configure 'sub_fields' below
'country'      => 'c',
'organization' => 'o',
'department'   => 'ou',
'jobtitle'     => 'title',
'notes'        => 'description',
// these currently don't work:
// 'phone:workfax' => 'facsimileTelephoneNumber',
// 'photo'         => 'jpegPhoto',
// 'manager'       => 'manager',
// 'assistant'     => 'secretary',
),
// Map of contact sub-objects (attribute name => objectClass(es)), e.g. 'c' => 'country'
'sub_fields' => array(),
// Generate values for the following LDAP attributes automatically when creating a new record
'autovalues' => array(
// 'uid'  => 'md5(microtime())',               // You may specify PHP code snippets which are then eval'ed 
// 'mail' => '{givenname}.{sn}@mydomain.com',  // or composite strings with placeholders for existing attributes
),
'sort'          => 'cn',    // The field to sort the listing by.
'scope'         => 'sub',   // search mode: sub|base|list
'filter'        => '(objectClass=inetOrgPerson)',      // used for basic listing (if not empty) and will be &'d with search queries. example: status=act
'fuzzy_search'  => true,    // server allows wildcard search
'vlv'           => false,   // Enable Virtual List View to more efficiently fetch paginated data (if server supports it)
'numsub_filter' => '(objectClass=organizationalUnit)',   // with VLV, we also use numSubOrdinates to query the total number of records. Set this filter to get all numSubOrdinates attributes for counting
'sizelimit'     => '0',     // Enables you to limit the count of entries fetched. Setting this to 0 means no limit.
'timelimit'     => '0',     // Sets the number of seconds how long is spend on the search. Setting this to 0 means no limit.
'referrals'     => true|false,  // Sets the LDAP_OPT_REFERRALS option. Mostly used in multi-domain Active Directory setups

// definition for contact groups (uncomment if no groups are supported)
// for the groups base_dn, the user replacements %fu, %u, $d and %dc work as for base_dn (see above)
// if the groups base_dn is empty, the contact base_dn is used for the groups as well
// -> in this case, assure that groups and contacts are separated due to the concernig filters! 
'groups'        => array(
'base_dn'     => '',
'scope'       => 'sub',   // search mode: sub|base|list
'filter'      => '(objectClass=groupOfNames)',
'object_classes' => array("top", "groupOfNames"),
'member_attr'  => 'member',   // name of the member attribute, e.g. uniqueMember
'name_attr'    => 'cn',       // attribute to be used as group name
),
);
*/

// An ordered array of the ids of the addressbooks that should be searched
// when populating address autocomplete fields server-side. ex: array('sql','Verisign');
$rcmail_config['autocomplete_addressbooks'] = array('sql');

// The minimum number of characters required to be typed in an autocomplete field
// before address books will be searched. Most useful for LDAP directories that
// may need to do lengthy results building given overly-broad searches
$rcmail_config['autocomplete_min_length'] = 1;

// Number of parallel autocomplete requests.
// If there's more than one address book, n parallel (async) requests will be created,
// where each request will search in one address book. By default (0), all address
// books are searched in one request.
$rcmail_config['autocomplete_threads'] = 0;

// Max. numer of entries in autocomplete popup. Default: 15.
$rcmail_config['autocomplete_max'] = 15;

// show address fields in this order
// available placeholders: {street}, {locality}, {zipcode}, {country}, {region}
$rcmail_config['address_template'] = '{street}<br/>{locality} {zipcode}<br/>{country} {region}';

// Matching mode for addressbook search (including autocompletion)
// 0 - partial (*abc*), default
// 1 - strict (abc)
// 2 - prefix (abc*)
// Note: For LDAP sources fuzzy_search must be enabled to use 'partial' or 'prefix' mode
$rcmail_config['addressbook_search_mode'] = 0;

// ----------------------------------
// USER PREFERENCES
// ----------------------------------

// Use this charset as fallback for message decoding
$rcmail_config['default_charset'] = 'UTF-8';

// skin name: folder from skins/
$rcmail_config['skin'] = 'larry';

// show up to X items in messages list view
$rcmail_config['mail_pagesize'] = 50;

// show up to X items in contacts list view
$rcmail_config['addressbook_pagesize'] = 50;

// sort contacts by this col (preferably either one of name, firstname, surname)
$rcmail_config['addressbook_sort_col'] = 'surname';

// the way how contact names are displayed in the list
// 0: display name
// 1: (prefix) firstname middlename surname (suffix)
// 2: (prefix) surname firstname middlename (suffix)
// 3: (prefix) surname, firstname middlename (suffix)
$rcmail_config['addressbook_name_listing'] = 0;

// use this timezone to display date/time
// valid timezone identifers are listed here: php.net/manual/en/timezones.php
// 'auto' will use the browser's timezone settings
$rcmail_config['timezone'] = 'auto';

// prefer displaying HTML messages
$rcmail_config['prefer_html'] = true;

// display remote inline images
// 0 - Never, always ask
// 1 - Ask if sender is not in address book
// 2 - Always show inline images
$rcmail_config['show_images'] = 0;

// open messages in new window
$rcmail_config['message_extwin'] = false;

// open message compose form in new window
$rcmail_config['compose_extwin'] = false;

// compose html formatted messages by default
// 0 - never, 1 - always, 2 - on reply to HTML message, 3 - on forward or reply to HTML message
$rcmail_config['htmleditor'] = 0;

// show pretty dates as standard
$rcmail_config['prettydate'] = true;

// save compose message every 300 seconds (5min)
$rcmail_config['draft_autosave'] = 300;

// default setting if preview pane is enabled
$rcmail_config['preview_pane'] = false;

// Mark as read when viewed in preview pane (delay in seconds)
// Set to -1 if messages in preview pane should not be marked as read
$rcmail_config['preview_pane_mark_read'] = 0;

// Clear Trash on logout
$rcmail_config['logout_purge'] = false;

// Compact INBOX on logout
$rcmail_config['logout_expunge'] = false;

// Display attached images below the message body 
$rcmail_config['inline_images'] = true;

// Encoding of long/non-ascii attachment names:
// 0 - Full RFC 2231 compatible
// 1 - RFC 2047 for 'name' and RFC 2231 for 'filename' parameter (Thunderbird's default)
// 2 - Full 2047 compatible
$rcmail_config['mime_param_folding'] = 1;

// Set true if deleted messages should not be displayed
// This will make the application run slower
$rcmail_config['skip_deleted'] = false;

// Set true to Mark deleted messages as read as well as deleted
// False means that a message's read status is not affected by marking it as deleted
$rcmail_config['read_when_deleted'] = true;

// Set to true to never delete messages immediately
// Use 'Purge' to remove messages marked as deleted
$rcmail_config['flag_for_deletion'] = false;

// Default interval for auto-refresh requests (in seconds)
// These are requests for system state updates e.g. checking for new messages, etc.
// Setting it to 0 disables the feature.
$rcmail_config['refresh_interval'] = 60;

// If true all folders will be checked for recent messages
$rcmail_config['check_all_folders'] = false;

// If true, after message delete/move, the next message will be displayed
$rcmail_config['display_next'] = true;

// 0 - Do not expand threads 
// 1 - Expand all threads automatically 
// 2 - Expand only threads with unread messages 
$rcmail_config['autoexpand_threads'] = 0;

// When replying:
// -1 - don't cite the original message
// 0  - place cursor below the original message
// 1  - place cursor above original message (top posting)
$rcmail_config['reply_mode'] = 0;

// When replying strip original signature from message
$rcmail_config['strip_existing_sig'] = true;

// Show signature:
// 0 - Never
// 1 - Always
// 2 - New messages only
// 3 - Forwards and Replies only
$rcmail_config['show_sig'] = 1;

// Use MIME encoding (quoted-printable) for 8bit characters in message body
$rcmail_config['force_7bit'] = false;

// Defaults of the search field configuration.
// The array can contain a per-folder list of header fields which should be considered when searching
// The entry with key '*' stands for all folders which do not have a specific list set.
// Please note that folder names should to be in sync with $rcmail_config['default_folders']
$rcmail_config['search_mods'] = null;  // Example: array('*' => array('subject'=>1, 'from'=>1), 'Sent' => array('subject'=>1, 'to'=>1));

// Defaults of the addressbook search field configuration.
$rcmail_config['addressbook_search_mods'] = null;  // Example: array('name'=>1, 'firstname'=>1, 'surname'=>1, 'email'=>1, '*'=>1);

// 'Delete always'
// This setting reflects if mail should be always deleted
// when moving to Trash fails. This is necessary in some setups
// when user is over quota and Trash is included in the quota.
$rcmail_config['delete_always'] = false;

// Directly delete messages in Junk instead of moving to Trash
$rcmail_config['delete_junk'] = false;

// Behavior if a received message requests a message delivery notification (read receipt)
// 0 = ask the user, 1 = send automatically, 2 = ignore (never send or ask)
// 3 = send automatically if sender is in addressbook, otherwise ask the user
// 4 = send automatically if sender is in addressbook, otherwise ignore
$rcmail_config['mdn_requests'] = 0;

// Return receipt checkbox default state
$rcmail_config['mdn_default'] = 0;

// Delivery Status Notification checkbox default state
// Note: This can be used only if smtp_server is non-empty
$rcmail_config['dsn_default'] = 0;

// Place replies in the folder of the message being replied to
$rcmail_config['reply_same_folder'] = false;

// Sets default mode of Forward feature to "forward as attachment"
$rcmail_config['forward_attachment'] = false;

// Defines address book (internal index) to which new contacts will be added
// By default it is the first writeable addressbook.
// Note: Use '0' for built-in address book.
$rcmail_config['default_addressbook'] = null;

// Enables spell checking before sending a message.
$rcmail_config['spellcheck_before_send'] = false;

// Skip alternative email addresses in autocompletion (show one address per contact)
$rcmail_config['autocomplete_single'] = false;

// Default font for composed HTML message.
// Supported values: Andale Mono, Arial, Arial Black, Book Antiqua, Courier New,
// Georgia, Helvetica, Impact, Tahoma, Terminal, Times New Roman, Trebuchet MS, Verdana
$rcmail_config['default_font'] = 'Verdana';

// end of config file
