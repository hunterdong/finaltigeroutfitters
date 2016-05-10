<?php
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
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/home/hunterd/public_html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'hunterd_wp585');

/** MySQL database username */
define('DB_USER', 'hunterd_wp585');

/** MySQL database password */
define('DB_PASSWORD', '3(p6P1S.DR');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'rwf3rb2oenkcphhno8wbjud7sj2vnogcbzchygxqrjzp7ixovhhmmrmzfrpkmyqy');
define('SECURE_AUTH_KEY',  'xyzydjlcjw0nv4mhfhqgopos9lk7i2qu8xeitlcglssr1xrfmrjeinrq8efymmh5');
define('LOGGED_IN_KEY',    'juwu1czfjxoiyhdcrsxlo0sohnuzxdeiinewm9bkmuyy1znxm7urx1k7rlqcheu6');
define('NONCE_KEY',        'v0jcrxtldrklqdhzotpx6fiv5gbxxeugyaqn1pjqsjv7zn1pjwd5fcckdf5f7c71');
define('AUTH_SALT',        '3eafeple8tyjm1f7n9eofhtodlargpldivfeet3gwzbraffhmqvukfkkr7ummtea');
define('SECURE_AUTH_SALT', 'boaaomtmsrprnh1zz6mwipnnjndeifbibl5hwjd7xaow5j6vopyhsupwhbr4enat');
define('LOGGED_IN_SALT',   '1qqpfynmfuapg5gzwp7nco8jfsfctperdkau2o1slkmpeq6imerqohqlcqhhrtap');
define('NONCE_SALT',       'qnvjb9w1wdad5ldlkgqhdgy3brs8momvim18lukyzag9go1uttayh5qv0nn92g6o');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpyv_';

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
ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
