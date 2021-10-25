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
define( 'DB_NAME', 'i6062861_wp5' );

/** MySQL database username */
define( 'DB_USER', 'i6062861_wp5' );

/** MySQL database password */
define( 'DB_PASSWORD', 'V.LpYdjV58jGvTQZpvD03' );

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
define('AUTH_KEY',         'Ii6uPDwj2xj1XvmUoFusJwULDK8cvII8JU3f5Mnf8S7aKB0qNfDMbiHwKk4QPk7F');
define('SECURE_AUTH_KEY',  'fcwWCHAaTKx23NNfqzjJJGUOwD4MRCEd7EBxp4NrbhUuhfqru9q9VhNjs4KvNxDK');
define('LOGGED_IN_KEY',    'qxv7sdWtvARxvuaZb6vVbKx51dfkgI5lXh6FW42soezJ3CTHyUc1JU4HOIdg04Ik');
define('NONCE_KEY',        'n0Dy7KkpFDV6EMz7JNZZ6d1klTo06UM3xjrJE5HXSq4pgYIMF4wqnV9h7mUOCOAC');
define('AUTH_SALT',        'm35276sQnMPU98uM8gXt3Q9YwfLzaD8MzgBfcZVBk7hdf3yV3vFlq2MNwEKDLaTe');
define('SECURE_AUTH_SALT', 'zwD8Du05ghTfXhRcdjM2oxnUoMU5RUemp1n6gjUIgODNhDJLg0VCcpxPOrDfPPjV');
define('LOGGED_IN_SALT',   'AXAsopYovD2LPUCRq1RQlANdvCb8edi85XYFu7yHHxmHsqafzccpJ6Rgtvi3q8qI');
define('NONCE_SALT',       'GZJJmpkqhomwsRq8Rwb3vmU8rmFm7RnnDoEE6HJJxBIraWT8sZjUuoZs7olnSDrw');

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
