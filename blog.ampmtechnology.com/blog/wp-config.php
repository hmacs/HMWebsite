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
define( 'DB_NAME', 'i6062861_wp3' );

/** MySQL database username */
define( 'DB_USER', 'i6062861_wp3' );

/** MySQL database password */
define( 'DB_PASSWORD', 'K.K4WdR7RgQ4UkgzcYz70' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '35GE49DiTEGJUSpKmerXhp5xFW50POcwItsbKlTE2W8AuIaia4rkO6He1ohdr2ne');
define('SECURE_AUTH_KEY',  'P9lFE9RCmamH14G6NunWwjdZqunGmsl4tBaf02DDGstoXQ32O8enLDU427Gnrbeb');
define('LOGGED_IN_KEY',    'sVEFSXNxd05mfTGamwm66zwK1H6zYVN0Jlh5CGsBSTo7u6TyhXnZvUO8nsXd8YMh');
define('NONCE_KEY',        'B0Xn2fCiiunbZWDeEPg823PSQ1gnoKcj1RmVgeHWNQI1UuAUvvS1yCapepOkEiis');
define('AUTH_SALT',        'N6n3u4Ma8NRk1LcwMiGIdR3CSVz9fzcI9CCiXsEKMFjYfVMSQnm5iC6oYMSfmNCx');
define('SECURE_AUTH_SALT', '8nfLfDjUTccyv5xSgZCPifYeQ6jsSSERUypTlPpnR0eKFDlh8YnvLZ7oMZjW5Tpv');
define('LOGGED_IN_SALT',   '9y6dOC0xgpcSoql4Xsx0U6xrtlPZwjcCUlSCqHwgDMOnGId8j4kOdZAKZzK8h7vR');
define('NONCE_SALT',       'wbPcwPPUoS7lGfVVOtmLnW3UowmNFtqcS34WCm6cDuLG33bVD8upYvr8uQwihe3j');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed externally by Installatron.
 * If you remove this define() to re-enable WordPress's automatic background updating
 * then it's advised to disable auto-updating in Installatron.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
