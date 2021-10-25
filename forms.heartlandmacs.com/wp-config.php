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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'i6062861_wp2' );

/** MySQL database username */
define( 'DB_USER', 'i6062861_wp2' );

/** MySQL database password */
define( 'DB_PASSWORD', 'W.2TPVRyWddzGzEGRml93' );

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
define('AUTH_KEY',         'B7gFNU3h9Rr0iPC0ZVzKhiV4XN8YOIlhHqGqgV8dGARUo20aauc953LjbfOZX6Tu');
define('SECURE_AUTH_KEY',  'RNSWQ0EKT9UcVMNgnvoPHjD0IlZOQFI3zG3AOE80xFn8MsGppttPU9Q3KClkxz0O');
define('LOGGED_IN_KEY',    'hcuYXjvOVg4EOZI5vLDm9IjxwKz03EQWs0ZQ1Xm6r7E6nwgoA1DB061ZE2mqH7hG');
define('NONCE_KEY',        'kT0vbufsz3cqAPxmO3N9vdjpQQp1BEASCr9Tw2UQqn4iifpVhATpb3mIY1PoBi2T');
define('AUTH_SALT',        'Y94E9QRckVnoPMSQrdxh6PIcwzYk0vX0OIPXr2ay2a5gKRi7ubeEQsJIewgJOMXD');
define('SECURE_AUTH_SALT', 'ZhhfHPNOf6jWG8gwjm8kUPYaPq1xSBRdskkmauJrTuTm6NsluTkINnR9xIFCFu9Q');
define('LOGGED_IN_SALT',   'K9OPdj494umtxuaSwXx7wigS7y0ilC9zkjXPD9r05ebZwlLStwDY7ZdgQK4dmtuz');
define('NONCE_SALT',       'pDiDPSszUKSmfrFRVNn2483WH6pU3lIMadG7TdPDs6ElYTi41an0lSaCPIjlo8wr');

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
