<?php

//// RCMCardDAV Plugin Admin Settings

//// ** GLOBAL SETTINGS

// Disallow users to add / edit / delete custom addressbooks (default: false)
//
// If true, User cannot add custom addressbooks
// If false, user can add / edit / delete custom addressbooks
//
// This option only affects custom addressbooks. Preset addressbooks (see below)
// are not affected.
// $prefs['_GLOBAL']['fixed'] = true;

// When enabled, this option hides the 'CardDAV' section inside Preferences.
// $prefs['_GLOBAL']['hide_preferences'] = true;

// Scheme for storing the CardDAV passwords, in order from least to best security.
// Options:
// plain: store as plaintext
// base64: store encoded with base64 (default)
// des_key: store encrypted with global des_key of roundcube
// encrypted: store encrypted with IMAP password of the user
//            NOTE: if the IMAP password of the user changes, the stored
//             CardDAV passwords cannot be decrypted anymore and the user
//             needs to reenter them.
// $prefs['_GLOBAL']['pwstore_scheme'] = 'base64';

//// ** ADDRESSBOOK PRESETS

// Each addressbook preset takes the following form:
/*
$prefs['<Presetname>'] = array(
	// required attributes
	'name'         =>  '<Addressbook Name>',
	'username'     =>  '<CardDAV Username>',
	'password'     =>  '<CardDAV Password>',
	'url'          =>  '<CardDAV URL>',

	// optional attributes
	'active'       =>  <true or false>,
	'readonly'     =>  <true or false>,
	'refresh_time' => '<Refresh Time in Hours, Format HH[:MM[:SS]]>',

	// attributes that are fixed (i.e., not editable by the user) and
	// auto-updated for this preset
	'fixed'        =>  array( < 0 or more of the other attribute keys > ),

	// hide this preset from CalDAV preferences section so users can't even
	// see it
	'hide' => <true or false>,
);
*/

// All values in angle brackets <VALUE> have to be substituted.
//
// The meaning of the different parameters is as follows:
//
// <Presetname>: Unique preset name, must not be '_GLOBAL'. The presetname is
//               not user visible and only used for an internal mapping between
//               addressbooks created from a preset and the preset itself. You
//               should never change this throughout its lifetime.
//
// The following parameters are REQUIRED and need to be specified for any preset.
//
// name:         User-visible name of the addressbook. If the server provides
//               an additional display name for the addressbooks found for the
//               preset, it will be appended in brackets to this name, except
//               if carddav_name_only is true (see below).
//
// username:     CardDAV username to access the addressbook. Set this setting
//               to '%u' to use the roundcube username.
//               In case one uses an email address as username there is the
//               additional option to choose '%l', which will only use the
//               local part of the username (eg: user.name@example.com will
//               become user.name).
//               Also, %d is available to get only the domain part of the
//               username (eg: user.name@example.com will become example.com).
//
// password:     CardDAV password to access the addressbook. Set this setting
//               to '%p' to use the roundcube password. The password will not
//               be stored in the database when using %p.
//
// url:          URL where to find the CardDAV addressbook(s). If the given URL
//               refers directly to an addressbook, only this single
//               addressbook will be added. If the URL points somewhere in the
//               CardDAV space, but _not_ to the location of a particular
//               addressbook, the server will be queried for the available
//               addressbooks and all of them will be added. You can use %u
//               within the URL as a placeholder for the CardDAV username.
//               '%l' works the same way as it does for the username field.
//
// The following parameters are OPTIONAL and need to be specified only if the default
// value is not acceptable.
//
// active:       If this parameter is false, the addressbook is not used by roundcube
//               unless the user changes this setting.
//               Default: true
//
// carddav_name_only:
//               If this parameter is true, only the server provided displayname
//               is used for addressbooks created from this preset, except if
//               the server does not provide a display name.
//               Default: false
//
// readonly:     If this parameter is true, the addressbook will only be
//               accessible in read-only mode, i.e., the user will not be able
//               to add, modify or delete contacts in the addressbook.
//               Default: false
//
// refresh_time: Time interval for that cached versions of the addressbook
//               entries should be used, in hours. After this time interval has
//               passed since the last pull from the server, it will be
//               refreshed when the addressbook is accessed the next time.
//               Default: 01:00:00
//
// fixed:        Array of parameter keys that must not be changed by the user.
//               Note that only fixed parameters will be automatically updated
//               for existing addressbooks created from presets. Otherwise the
//               user may already have changed the setting, and his change
//               would be lost. You can add any of the above keys, but it the
//               setting only affects parameters that can be changed via the
//               settings pane (e.g., readonly cannot be changed by the user
//               anyway). Still only parameters listed as fixed will
//               automatically updated if the preset is changed.
//               Default: empty, all settings modifiable by user
//
//               !!! WARNING: Only add 'url' to the list of fixed addressbooks
//                if it _directly_ points to an address book collection.
//                Otherwise, the plugin will initially lookup the URLs for the
//                collections on the server, and at the next login overwrite it
//                with the fixed value stored here. Therefore, if you change the
//                URL, you have two options:
//                1) If the new URL is a variation of the old one (e.g. hostname
//                 change), you can run an SQL UPDATE query directly in the
//                 database to adopt all addressbooks.
//                2) If the new URL is not easily derivable from the old one,
//                 change the key of the preset and change the URL. Addressbooks
//                 belonging to the old preset will be deleted upon the next
//                 login of the user and freshly created.
//
// hide:         Whether this preset should be hidden from the CalDAV listing
//               on the preferences page.


// How Preset Updates work
//
// Preset addressbooks are created for a user as she logs in.

//// ** ADDRESSBOOK PRESETS - EXAMPLE: Two Addressbook Presets

//// Preset 1: Personal
/*
$prefs['Personal'] = array(
	// required attributes
	'name'         =>  'Personal',
	// will be substituted for the roundcube username
	'username'     =>  '%u', 
	// will be substituted for the roundcube password
	'password'     =>  '%p',
	// %u will be substituted for the CardDAV username
	'url'          =>  'https://ical.example.org/caldav.php/%u/Personal',

	'active'       =>  true,
	'readonly'     =>  false,
	'refresh_time' => '02:00:00',

	'fixed'        =>  array( 'username' ),
	'hide'        =>  false,
);
*/

//// Preset 2: Corporate
/*
$prefs['Work'] = array(
	'name'         =>  'Corporate',
	'username'     =>  'CorpUser',
	'password'     =>  'C0rpPasswo2d',
	'url'          =>  'https://ical.example.org/caldav.php/%u/Corporate',

	'fixed'        =>  array( 'name', 'username', 'password' ),
	'hide'        =>  true,
);
*/
