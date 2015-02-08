<?php
/**
 *Clotilde Sourisseau functions and definitions
 *
 * @package Clotilde Sourisseau
 */

include_once(dirname(__FILE__).'/inc/F/config.php');
include_once(dirname(__FILE__).'/inc/F/Loader.php');
\F\Loader::autoload();
include_once(dirname(__FILE__).'/inc/F/project.php');

define('KEY_FOOTER_MENU', 'footer-menu');
define('KEY_MAIN_MENU', 'main-menu');
define('ID_PAGE_NEW_EVENT', 29);
define('WP_THEME__URL', get_template_directory_uri().'/');
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

function remove_recent_comment_style() {
    global $wp_widget_factory;
    remove_action(
        'wp_head',
        array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' )
    );
}
add_action( 'widgets_init', 'remove_recent_comment_style' );

if ( ! function_exists( 'clotilde_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function clotilde_setup() {

    remove_action('wp_head', 'wp_generator');

    add_filter('show_admin_bar', '__return_false');

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based onParislanuit, use a find and replace
	 * to change 'clotilde' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'clotilde', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'clotilde' ),
	) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link'
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'clotilde_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // clotilde_setup
add_action( 'after_setup_theme', 'clotilde_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function clotilde_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'clotilde' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'clotilde_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function clotilde_assets() {
    //wp_enqueue_style( 'clotilde-style', get_template_directory_uri().'/styles/main.css', null, null );

    //wp_enqueue_script('modernizr', get_template_directory_uri().'/scripts/vendor/Modernizr-2.8.2.js', null, null, false);

}
add_action( 'wp_enqueue_scripts', 'clotilde_assets' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';


/**
 * utils
 */
function trace($obj) {
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}

function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest";
}

/**
* Breadcrumb
*/
function breadcrumb() {
	global $post;

	if (is_page() && !is_front_page() || is_single() || is_category()) {
		echo '<ul>';
		echo '<li><a title="Accueil" rel="nofollow" href="'.get_option('siteurl').'">Accueil</a></li>';

		if (is_page()) {
			$ancestors = get_post_ancestors($post);

			if ($ancestors) {
				$ancestors = array_reverse($ancestors);

				foreach ($ancestors as $crumb) {
					echo '<li><a href="'.get_permalink($crumb).'">'.get_the_title($crumb).'</a></li>';
				}
			}
		}

		if (is_single()) {
			$category = get_the_category();
			echo '<li><a href="'.get_category_link($category[0]->cat_ID).'">'.$category[0]->cat_name.'</a></li>';
		}

		if (is_category()) {
			$category = get_the_category();
			echo '<li>'.$category[0]->cat_name.'</li>';
		}

		// Current page
		if (is_page() || is_single()) {
			echo '<li>'.get_the_title().'</li>';
		}

		echo '</ul>';

	} elseif (is_front_page()) {
	// Front page
		echo '<ul>';
		echo '<li><a title="Accueil" rel="nofollow" href="'.get_option('siteurl').'">Accueil</a></li>';
		echo '</ul>';
	}
}