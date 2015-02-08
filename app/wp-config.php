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
define('DB_NAME', 'wp_clotilde');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'xw$+(sQiW<d|^~!T7nZ^42 ?*D244_(?p&:-=b)0YA(t}x`bg|w(KKIC}aR~!^lC');
define('SECURE_AUTH_KEY',  '?6i0-f3CDXD3R?x-bX6ylMwM`2-2_6Y^9vJ|}r|NuGG;YZY;8&U/sz@}TQ{M)}UF');
define('LOGGED_IN_KEY',    'a[P,_J6n$aM_C1-OY%J7TLI] GYuA|$O8iHJ@lEd>NJD|&_]$KP1 r|6Hca9Aeq_');
define('NONCE_KEY',        '5aUnf*R!tm7K?~M]%UMGtG]d+dd7/<#kO+oh58E:M5~sTpkQmVS.|0Kh3Mi<F](6');
define('AUTH_SALT',        'wF=Z`plhe-d97M$V5m/y6kA*BfI}F3jfx?zF`RwJ`2-bhQ-j<p_C1UGy$6DKA7_o');
define('SECURE_AUTH_SALT', '6%&=odm8orVXm2WWnJ*0nySG+rt?OF<IQWuZlel>]F~v+#7-sKo+Kp,=4k^sQt;z');
define('LOGGED_IN_SALT',   'MTkliY(~O]T+h!.@Hx{=zy(V 4INDae|;Nsl.aYkJGF{tq#h&3w47J)5-/>TJ.|]');
define('NONCE_SALT',       's0k_La+r4YW9:-xp!JQH^|O0^9+w!t`4p{hh5?v]==wg$i5^UF`08+oC5M0GRK3M');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'clo_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

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