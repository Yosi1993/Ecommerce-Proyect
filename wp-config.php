<?php

/** WP 2FA plugin data encryption key. For more information please visit melapress.com */
define( 'WP2FA_ENCRYPT_KEY', 'GQKJ3+tzNLPxPLOAu6Is7w==' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ecommerce' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '7{).8An6wPmX-%0<m8JV:a*+,,;@?@$7;}R2P4$ (n<$1c?EYKc(Ls*ke?l0w8&/' );
define( 'SECURE_AUTH_KEY',  ']*DEDS$T[cq;o=Vbxqfe1JWPr&(7%=2j[`F3=9H&`p%.WZ,8z?b!}Po XhM@ tu=' );
define( 'LOGGED_IN_KEY',    '<H7C8o($s**IBQzF*o~/A+0l{GPCKL+.B-pwx6jA*I(p,}6d]shYq4oDExsgz3un' );
define( 'NONCE_KEY',        '!!$EXO%[TH%{g,PHWaL(g=:&-W7z7J0PQS_t4gC++8G>0 c<%PgZ;Fo^XCXNNmj/' );
define( 'AUTH_SALT',        'HZW;;bvyPktap!hJs$d{9r14rsV,~`fqs|~r<+FSI|Co8HnZ_&M||*}@5NiE1aR@' );
define( 'SECURE_AUTH_SALT', 'L~b*>G!~2BcHkWPe6Pqsj|6w8J0 !Vv~GAAN@TC>$=].qS{5]f&}]h#&(/E3{Vo+' );
define( 'LOGGED_IN_SALT',   '5f/6m%71JRnAOK$a!#<]eZWg,qASTe8xxuhZD-|Sq5f#eFV3/JRAZ,.zzW6oK#YI' );
define( 'NONCE_SALT',       '|ya/^@@eU3/iNRdb{l_|`i6$j 5}IF;2|Xv<u_AK/gfuInaFZ7S@NF7gVe&)S0=)' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
