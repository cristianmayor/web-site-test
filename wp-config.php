<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'wordpress_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'f1SN.Wu|6L>TbaSq3e6Jf:X;?u)Ma]`*sK_.Rkj{HsX0IxL<NxHv!1*Uo~aqDG<+' );
define( 'SECURE_AUTH_KEY',  '*9nk!I3#Rk`x%(T8^r/KcR_:A1i{UFJE7?/Yf-JHbJVjGBuNJ?Fiz->Mm:Mvp10r' );
define( 'LOGGED_IN_KEY',    ';R1 a.eL[r0:rN1D)l#AIZxs _ty>1IB&WN^s8TI83WfYiNWly[cJ#zc/rG;{NuJ' );
define( 'NONCE_KEY',        'V/=:(]NN%z++t}IsTq 4Sf*9)Ith|i-6i*9[^|,8a3lr]vo!xe/G9;[,mwA>lL<o' );
define( 'AUTH_SALT',        ')9^_o*Anww1}06>DqPkgsM{K?jZL@lx6Ihv|Wd^8<,uLK27-N!+} ~Y`%C)wUQAb' );
define( 'SECURE_AUTH_SALT', 'j#SM[!,QrX#2Re8qx[FZ:zn[QLOs[1IH#L1q7O]5r@^K~;zNvILB&9%;L>7%:`@x' );
define( 'LOGGED_IN_SALT',   'cYx]RxL?!5`{Mq>y>oWK.v+|]L,(#T%*XK #Y6A3{|{>W&#k>cLG!9V*yHSpPtm|' );
define( 'NONCE_SALT',       ']0p5;I?I??7Du`:ZM*G4FN{By#tKxmg!(GKaNC_Ll:PoqO=<2%ydwG2XpD;4`<4R' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
