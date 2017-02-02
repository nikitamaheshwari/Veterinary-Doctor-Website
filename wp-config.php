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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '#ONPAV5F|@(}6_cJ#CfHg)XY)gasKb$UE$)gU?3o}EG;^oGu])dTX*PnD0UTBAFf');
define('SECURE_AUTH_KEY',  ']ovAd5;7@TJ)vN9M}b;e1sBawvfoE;b1-o1`i<2=N/uFl}qeHkGwo-<T5Fs<_5$-');
define('LOGGED_IN_KEY',    '{xda:0`s |(< 1#Yg-s,,6+ v;f|fzu^&3y8a^N,A@nh2[xm`(2PeIgQ X.~?M:A');
define('NONCE_KEY',        '-5JKqlo[w$)X[*N]H4nGV93VM&vOiTM ni(/j2tB:mQw9>b,v+=wuH/{Kp+n6Wg8');
define('AUTH_SALT',        '3-X#R:6:c>Q|Bo#a`PlvP{ne3-u_6$@TR<}K+elF8S6OQvUz%@ATn]fvfTvzO!|D');
define('SECURE_AUTH_SALT', 'T_ES{L`iyoj07=/F:Tr>.Oa5FXd[?dvK@@GR*El}.={P`xo6|@;V(qTI-c&j;)nI');
define('LOGGED_IN_SALT',   'T]O$1[cZ?6/Y>E`Lkj<+0CBZa>p4O;3-#^#gX@*T0)zl3H=tRdy#-IiLB> @tGP9');
define('NONCE_SALT',       'dE#DM)ezQlI/tOIGw{h*7a )WQ{L:{GVLR`r42[2ajGh$eI1J-dV|=Em^ZC5vZ)/');

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
