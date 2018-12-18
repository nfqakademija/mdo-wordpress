<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '');

/** MySQL database username */
define('DB_USER', '');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'a2~Y<=bdSUQkfRq:lhDB|]U7P-w?kG}*MkASL0L1@a:|-Xpz!QxADnT&nEw:s,AF');
define('SECURE_AUTH_KEY',  ';OmMcfAr)4UR2igKa K0CL -d>q5Y!Yq p17{qrlhR>@l}+A8f?X8N>lDYH@!E~h');
define('LOGGED_IN_KEY',    '|e/pn !V*z9RR(jNH *2#`1HW!Rl/W33P;;<*Z*z8`Vo>Ew3?$wxx86:(_S$Q|dz');
define('NONCE_KEY',        'JtB_djwdR62uah4bJCF%c6rav|%Hpo>r oF!Z$<4$lcYGB=^5q3/Vi}O,<(@%p+D');
define('AUTH_SALT',        '.q>Nl%ua0!?PN?dA=`g!=#u;$rTSu]7hw CgxX/6ZKJOBe:SM;L=82fSHpe8zL6r');
define('SECURE_AUTH_SALT', 'WtTg |Kz1bRv4n*n4Ft=oxcksrL-;1.d#q4?`qj^tRzdl (?#dLqb ~1J=CmgLZq');
define('LOGGED_IN_SALT',   'U9iPd6s{jbC4O}Q&+z?0UqboRIOb`GNX4T*{h(/zS!NPw0[YR%biJIf=@jYr$~*b');
define('NONCE_SALT',       '6s>YJ59}do`#oc>aFsR(q*~T1.Op{RuieUIK%uC%DYr7:{1T5xix&76/jN<uyU]m');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
