<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'webmaste_wmu01');

/** MySQL database username */
define('DB_USER', 'webmaste_wmu01');

/** MySQL database password */
define('DB_PASSWORD', '63bpS2hPye');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'i0w6kwgvmhf0qupkzxlcu55o0s1ntlu5z9f9u5yt19fbi53keigghpfvnivxjrhk');
define('SECURE_AUTH_KEY',  'omenpr1wroog17ouvhejm0ycnzqo7aiqvx41tppgpdttact5qv5sgfcleyrtozrs');
define('LOGGED_IN_KEY',    'u5ah2xntprhxnr0opg7zb5vdboj9hhokkumzhr8rj2bpi54ca3ubj5z91zhiocwx');
define('NONCE_KEY',        'iscdtdmiksjqaarfw72dnmgd0d2pmdxn1rdrztwc4spg6vzporzckzjqldmrtyql');
define('AUTH_SALT',        'kmmcsudh5cydv3nzlopknak0b1ycp0uufi5xrvpfwtiyoygh9lamg5ppe2lb3nft');
define('SECURE_AUTH_SALT', 'os1dtajszntkufaoq07rmb96w4axzn6tcvxyxvrcu5rgllmlt8dygy8ocrwpqubz');
define('LOGGED_IN_SALT',   'qev2bpzxwupqf13svrmraz71lk28lnubzwjhekkixieyliulfckprzkoqf5brv3w');
define('NONCE_SALT',       'gq0i2trb2dictfxcquh5wgbnt3py2jgfam7ymdogpk9xbodpghitaimqaescf9du');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
