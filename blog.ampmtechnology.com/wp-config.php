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
define( 'DB_NAME', 'i6062861_wp1' );

/** MySQL database username */
define( 'DB_USER', 'i6062861_wp1' );

/** MySQL database password */
define( 'DB_PASSWORD', 'U.RsImwiTadPdL0WrwY22' );

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
define('AUTH_KEY',         'p0FKfHs21ICl5ma3wT50vlYsMNYjeRSWuSBgn6Mkff0X9JytrOO2Onnx7GVf4vu2');
define('SECURE_AUTH_KEY',  'GjRUEOBE95Vx5IB5J5ruqyuy2efT6e3XZY6dlNjE1pWe0QyKMIJhdOQk5yLsgD22');
define('LOGGED_IN_KEY',    'dE6Y82MCuIEqR3ZHHBSekuh07JigvXG0fXU4TWr1gKMsommaVZZuhgrMQMTTEzLi');
define('NONCE_KEY',        'kZSTZ9Km4CP0Y17ELW59WIIwkFeBJLr8pKwClaqcbxXRyUgLrs3hlyHooOnik7HA');
define('AUTH_SALT',        'kMuIgxJYbJAeScM2hOk1FASbb3L5NqZn8uw94gDv94kkOMNm7Tvsu5DQV4aOj0tD');
define('SECURE_AUTH_SALT', 'vbOpoj6RikxtQhsllhp1WEm2kQU1oK6OmlYjn917dnaJVpbsPvRM20WvV3OqpGE1');
define('LOGGED_IN_SALT',   'kTQbRS0DxFGBxM446fBHzd0yLaACmXeeQQqlnsRTHOD69SSWLtvd6bFXquuwWlKe');
define('NONCE_SALT',       'EV5gYBKCxabOJJfcUMxXrGPB98oNCgnMU0lahOBK4o5NK7WlbURqjVYNa1NtRHvV');

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
