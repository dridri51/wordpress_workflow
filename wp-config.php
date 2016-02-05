<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpres');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'xWo+sJOAos*>rAIh9L?Zxc;5dSu/t-F}]x3^)^<!OHS0it)}$=v0c<_Rnn71/`Z_');
define('SECURE_AUTH_KEY',  'j$9EdH+)mOe6M(lVf8:A|5f;[pS4q%_zg`%>y?fHj&T*#>4ApJA<Pe}xlczeM}l9');
define('LOGGED_IN_KEY',    '|g@/jcT.Q{8b,DlIN.Zc.l(JwC8vD^74K}|5`!_-.xQAD+=TM$Y>dJ)e^3!4e_C&');
define('NONCE_KEY',        '<YpAgS7Foz@F 9`dCv8,?+tl6|a|TM;yoS;ER8%C[1->O{[$Bn?XjJ@9p|?H3tV[');
define('AUTH_SALT',        '.{<{SaKcqXR:hU(qx{l;h_qy*aV]{-0?2=VuRS3A@(Staq+#l:()x4CDIRPt=*WA');
define('SECURE_AUTH_SALT', 'si^-~#rPXT2<{T;+r3/es1:/tei}k)$+xE](PRy^/n)x=FdT3H$gPL[{K+If&:;O');
define('LOGGED_IN_SALT',   'hZ-h-,{g|I`3.{7{w).cRW%X6G3F+PtMf@tk9H.[GCjAf5/4ZCIxPw-60[t)>#;k');
define('NONCE_SALT',       '+TK-0Vso2~yQiuxq=p5Tw5u;%^-rQl,$K53~k/ WX6b%4}>2x$],<Ec-Ll8`%8S+');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');