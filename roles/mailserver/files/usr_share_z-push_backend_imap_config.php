<?php
/***********************************************
* File      :   config.php
* Project   :   Z-Push
* Descr     :   IMAP backend configuration file
*
* Created   :   27.11.2012
*
* Copyright 2007 - 2016 Zarafa Deutschland GmbH
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License, version 3,
* as published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* Consult LICENSE file for details
************************************************/

// ************************
//  BackendIMAP settings
// ************************

// Defines the server to which we want to connect
define('IMAP_SERVER', 'localhost');

// connecting to default port (143)
define('IMAP_PORT', 993);

// best cross-platform compatibility (see http://php.net/imap_open for options)
// define('IMAP_OPTIONS', '/notls/norsh');
define('IMAP_OPTIONS', '/ssl/novalidate-cert');


// Mark messages as read when moving to Trash.
//      BE AWARE that you will lose the unread flag, but some mail clients do this so the Trash folder doesn't get boldened
define('IMAP_AUTOSEEN_ON_DELETE', false);


// IMPORTANT: BASIC IMAP FOLDERS [ask your mail admin]
        // We can have diferent cases (case insensitive):
        // 1.
        //      inbox
        //      sent
        //      drafts
        //      trash
        // 2.
        //      inbox
        //      common.sent
        //      common.drafts
        //      common.trash
        // 3.
        //      common.inbox
        //      common.sent
        //      common.drafts
        //      common.trash
        // 4.
        //      common
        //      common.sent
        //      common.drafts
        //      common.trash
        //
        // gmail is a special case, where the default folders are under the [gmail] prefix and the folders defined by the user are under INBOX.
        // This configuration seems to work:
        //      define('IMAP_FOLDER_PREFIX', '');
        //      define('IMAP_FOLDER_PREFIX_IN_INBOX', false);
        //      define('IMAP_FOLDER_INBOX', 'INBOX');
        //      define('IMAP_FOLDER_SENT', '[Gmail]/Sent');
        //      define('IMAP_FOLDER_DRAFT', '[Gmail]/Drafts');
        //      define('IMAP_FOLDER_TRASH', '[Gmail]/Trash');
        //      define('IMAP_FOLDER_SPAM', '[Gmail]/Spam');
        //      define('IMAP_FOLDER_ARCHIVE', '[Gmail]/All Mail');

// Since I know you won't configure this, I will raise an error unless you do.
// When configured set this to true to remove the error
define('IMAP_FOLDER_CONFIGURED', false);

// Folder prefix is the common part in your names (3, 4)
define('IMAP_FOLDER_PREFIX', '');

// Inbox will have the preffix preppend (3 & 4 to true)
define('IMAP_FOLDER_PREFIX_IN_INBOX', false);

// Inbox folder name (case doesn't matter) - (empty in 4)
define('IMAP_FOLDER_INBOX', 'INBOX');

// Sent folder name (case doesn't matter)
define('IMAP_FOLDER_SENT', 'SENT');

// Draft folder name (case doesn't matter)
define('IMAP_FOLDER_DRAFT', 'DRAFTS');

// Trash folder name (case doesn't matter)
define('IMAP_FOLDER_TRASH', 'TRASH');

// Spam folder name (case doesn't matter). Only showed as special by iOS devices
define('IMAP_FOLDER_SPAM', 'SPAM');

// Archive folder name (case doesn't matter). Only showed as special by iOS devices
define('IMAP_FOLDER_ARCHIVE', 'ARCHIVE');



// forward messages inline (default true - inlined)
define('IMAP_INLINE_FORWARD', true);

// list of folders we want to exclude from sync. Names, or part of it, separated by |
// example: dovecot.sieve|archive|spam
define('IMAP_EXCLUDED_FOLDERS', '');



// overwrite the "from" header with some value
// options:
//        ''              - do nothing, use the From header
//        'username'      - the username will be set (usefull if your login is equal to your emailaddress)
//        'domain'        - the value of the "domain" field is used
//        'sql'           - the username will be the result of a sql query. REMEMBER TO INSTALL PHP-PDO AND PHP-DATABASE
//        'ldap'          - the username will be the result of a ldap query. REMEMBER TO INSTALL PHP-LDAP!!
//        '@mydomain.com' - the username is used and the given string will be appended
define('IMAP_DEFAULTFROM', '');

// DSN: formatted PDO connection string
//    mysql:host=xxx;port=xxx;dbname=xxx
// USER: username to DB
// PASSWORD: password to DB
// OPTIONS: array with options needed
// QUERY: query to execute
// FIELDS: columns in the query
// FROM: string that will be the from, replacing the column names with the values
define('IMAP_FROM_SQL_DSN', '');
define('IMAP_FROM_SQL_USER', '');
define('IMAP_FROM_SQL_PASSWORD', '');
define('IMAP_FROM_SQL_OPTIONS', serialize(array(PDO::ATTR_PERSISTENT => true)));
define('IMAP_FROM_SQL_QUERY', "select first_name, last_name, mail_address from users where mail_address = '#username@#domain'");
define('IMAP_FROM_SQL_FIELDS', serialize(array('first_name', 'last_name', 'mail_address')));
define('IMAP_FROM_SQL_EMAIL', '#mail_address');
define('IMAP_FROM_SQL_FROM', '#first_name #last_name <#mail_address>');
define('IMAP_FROM_SQL_FULLNAME', '#first_name #last_name');

// SERVER: ldap server
// SERVER_PORT: ldap port
// USER: dn to use for connecting
// PASSWORD: password
// QUERY: query to execute
// FIELDS: columns in the query
// FROM: string that will be the from, replacing the field names with the values
define('IMAP_FROM_LDAP_SERVER', 'localhost');
define('IMAP_FROM_LDAP_SERVER_PORT', '389');
define('IMAP_FROM_LDAP_USER', 'cn=zpush,ou=servers,dc=zpush,dc=org');
define('IMAP_FROM_LDAP_PASSWORD', 'password');
define('IMAP_FROM_LDAP_BASE', 'dc=zpush,dc=org');
define('IMAP_FROM_LDAP_QUERY', '(mail=#username@#domain)');
define('IMAP_FROM_LDAP_FIELDS', serialize(array('givenname', 'sn', 'mail')));
define('IMAP_FROM_LDAP_EMAIL', '#mail');
define('IMAP_FROM_LDAP_FROM', '#givenname #sn <#mail>');
define('IMAP_FROM_LDAP_FULLNAME', '#givenname #sn');



// Method used for sending mail
// mail => mail() php function
// sendmail => sendmail executable
// smtp => direct connection against SMTP
define('IMAP_SMTP_METHOD', 'mail');

global $imap_smtp_params;
// SMTP Parameters
//      mail : no params
$imap_smtp_params = array();
//      sendmail
//$imap_smtp_params = array('sendmail_path' => '/usr/bin/sendmail', 'sendmail_args' => '-i');
//      smtp
//          "host"              - The server to connect. Default is localhost.
//          "port"              - The port to connect. Default is 25.
//          "auth"              - Whether or not to use SMTP authentication. Default is FALSE.
//          "username"          - The username to use for SMTP authentication. "imap_username" for using the same username as the imap server
//          "password"          - The password to use for SMTP authentication. "imap_password" for using the same password as the imap server
//          "localhost"         - The value to give when sending EHLO or HELO. Default is localhost
//          "timeout"           - The SMTP connection timeout. Default is NULL (no timeout).
//          "verp"              - Whether to use VERP or not. Default is FALSE.
//          "debug"             - Whether to enable SMTP debug mode or not. Default is FALSE.
//          "persist"           - Indicates whether or not the SMTP connection should persist over multiple calls to the send() method.
//          "pipelining"        - Indicates whether or not the SMTP commands pipelining should be used.
//          "verify_peer"       - Require verification of SSL certificate used. Default is TRUE.
//          "verify_peer_name"  - Require verification of peer name. Default is TRUE.
//          "allow_self_signed" - Allow self-signed certificates. Requires verify_peer. Default is FALSE.
//$imap_smtp_params = array('host' => 'localhost', 'port' => 25, 'auth' => false);
// If you want to use SSL with port 25 or port 465 you must preppend "ssl://" before the hostname or IP of your SMTP server
// IMPORTANT: To use SSL you must use PHP 5.1 or later, install openssl libs and use ssl:// within the host variable
// IMPORTANT: To use SSL with PHP 5.6 you should set verify_peer, verify_peer_name and allow_self_signed
//$imap_smtp_params = array('host' => 'ssl://localhost', 'port' => 465, 'auth' => true, 'username' => 'imap_username', 'password' => 'imap_password');
// If you want to use STARTTLS when the server is supporting it, you just need to enable authentication on a non SSL host variable.
//$imap_smtp_params = array('host' => 'localhost', 'port' => 587, 'auth' => true, 'username' => 'imap_username', 'password' => 'imap_password');


// If you are using IMAP_SMTP_METHOD = mail or sendmail and your sent messages are not correctly displayed you can change this to "\n".
//   BUT, it doesn't comply with RFC 2822 and will break if using smtp method
define('MAIL_MIMEPART_CRLF', "\r\n");


// A file containing file mime types->extension mappings.
//  SELINUX users: make sure the file has a security context accesible by your apache/php-fpm process
define('SYSTEM_MIME_TYPES_MAPPING', '/etc/mime.types');


// Use BackendCalDAV for Meetings. You cannot hope to get that functionality working without a caldav backend.
define('IMAP_MEETING_USE_CALDAV', false);

// If your IMAP server allows authenticating via GSSAPI, php-imap will not fall back properly to other authentication
// methods and you will be unable to log in. Uncomment the following line to disable that authentication method.
// Multiple methods can be specified as a comma-separated string.
// define('IMAP_DISABLE_AUTHENTICATOR', 'GSSAPI');
