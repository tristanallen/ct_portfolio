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
define('DB_NAME', 'furrynoodles');

/** MySQL database username */
define('DB_USER', 'furrynoodles');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '0S>E.&ax&Sj> 61q(@vjQtUE$BPke&W4/$,5A#4b)t/aU%ZLTe?h0w/G{DX1Bot;');
define('SECURE_AUTH_KEY',  'tnD5KL$F*85j[~;p4.Pl@=lqyvVC*/k>Igb{E@2cNouhxv%VRWalfH@.zEJ-(B5>');
define('LOGGED_IN_KEY',    ':%#t{ZyO29RZJ3q[_|= :o6j0LVb=S~pDr_B=.i:IQ*`gK+m0CKwg7J#6(B!6O$^');
define('NONCE_KEY',        'b|M,;tybJG$9FqXHR)-q861;>.=?wkF edk|v&mZ{{y=Iurz~#r.+.O^8ws*L<7.');
define('AUTH_SALT',        '@]Y90Lb_4-]DSz:r)Sg$E9|8~AqtK@pSKwW>4twSLzmh9r@C1>LJ`@I,[)J-hyd&');
define('SECURE_AUTH_SALT', 'z_W|pR;mfH:m{XVA*lC3CI`r{}Hcz%Jm.]cIQ$E/iU$wGuFzg;Ih)cg=kI$oj)8 ');
define('LOGGED_IN_SALT',   ':<^Z43FxF+*GO1Amw+jE$!*EmIf+kq|mnJdj)LZ)Eup.P=bYpf~{Vp*Tolr&Vtfk');
define('NONCE_SALT',       'DvKm2LS$q)~1]z~@yx41>3lg3WrHI,^V qJi6Hm6wh?L(g!_q4=;oJsM<0motaRt');

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
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
