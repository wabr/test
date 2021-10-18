<?php
/**
 * The base configuration for wordpress
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
 * @package wordpress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for wordpress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ wordpress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bRxjL(PcKutTU]K4;bK+wo#?:t~2B:Pi6{84d`_NT%YPN-t7VL= ##65JJ^uZdPC' );
define( 'SECURE_AUTH_KEY',  'CzHTeh,pTZ3j|(_mf5f;6w_JQZ+H2/@CS>:!n7^N6lwY&}bt8vkXCqLVZW0%gs*{' );
define( 'LOGGED_IN_KEY',    '}}`|Mlq`,2Fbsqpl_bv)E;`}XYXe#>6DOtKP1k;hr}+=ZLuh?} sg `DInvsj_,&' );
define( 'NONCE_KEY',        'e+~+M6;*a>qI}tkpSe|jyOT=jNe@0L_BX/N(XaJVN$=3L)5bzU%%;5eh;r%(BXsC' );
define( 'AUTH_SALT',        'YYY-bd9hbo(O_KuU~~/1@4lDvoGZf(eUKtbwi^_y?{aGR8TT v2M_7bF#ypNeO%q' );
define( 'SECURE_AUTH_SALT', 'Ku=t-IwKT1btq/}}<$QAc8i|rSeJPAriu#`0XbkM(@ofC~h6VcCa$d>B9xt/=AcL' );
define( 'LOGGED_IN_SALT',   'ta_D6vPxAo3oq|g36ZfqA1#:]m,q5bF9c4K(fNztoyN[AZxn]*0#/A.0AwI?5%<%' );
define( 'NONCE_SALT',       '&7bOTE.1}r&K#&P7a7<2N++T-Ja!t+GyDlv|7Y|&:wnPgD}ACCnuS4Zw?G&4HO7V' );

/**#@-*/

/**
 * wordpress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: wordpress debugging mode.
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

/** Absolute path to the wordpress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up wordpress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
