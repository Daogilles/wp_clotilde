<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package clotilde
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
if(!isAjax()) {
?><!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="fr" class="no-js lt-ie9 lt-ie8 lt-ie7 oldie"> <![endif]-->
<!--[if IE 7]>         <html lang="fr" class="no-js lt-ie9 lt-ie8 oldie"> <![endif]-->
<!--[if IE 8]>         <html lang="fr" class="no-js lt-ie9 oldie"> <![endif]-->
<!--[if IE 9]>         <html lang="fr" class="no-js lt-ie10 oldie"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="fr" class="no-js"> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php
    wp_head();
    \F\Renderer::getInstance()->showStyles();
    ?>
    <script>
        (function(w) {
            w.CLO = w.CLO || {};
            w.CLO.CONFIG = {
                PATH_URL : '<?php echo PATH_URL; ?>',
                ROOT_URL : '<?php echo rtrim(get_site_url(), '/').'/'; ?>',
                THEME_URL : '<?php echo WP_THEME__URL; ?>',
            };
            w.CLO.PAGES_VIEWS = {
              'home' : 'Home',
              'gallery' : 'Gallery',
              'services' : 'Services',
              'contact' : 'Contact'
            };
            w.CLO.PAGES_MODELS = {
            };
            CLO.config = {
                browser: undefined,
                device: undefined,

                screen: {
                    width: undefined,
                    height: undefined
                },

                itemSize: undefined,
                activeItem: undefined,
                currentItem: undefined,

                globalDuration: 500,
                fastDuration: 250
            };
        })(window);
    </script>
    <?php
    \F\Renderer::getInstance()->showScripts(true);
    ?>
</head>

<body <?php body_class(); ?>>
<div id="header-wrapper" class="max">

    <header id="header" class="wrapper" role="banner">
        
        
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="logo">
            <img src="<?php echo WP_THEME__URL; ?>img/logo@2x.png" alt="Logo Clotilde Sourisseau"/>
        </a>
        


		<nav id="nav" class="main-navigation" role="navigation">
			<?php wp_nav_menu( array( 'menu' => KEY_MAIN_MENU, 'container' => '' ) ); ?>
		</nav>
        
	</header>
    <a id="nav_resp">
        <span></span>
        <span></span>
        <span></span>
    </a>
</div>
    <div id="menu_resp">
        <?php wp_nav_menu( array( 'menu' => KEY_MAIN_MENU, 'container' => '' ) ); ?>
    </div>
    <div id="loader" class="show">
        <div id="loader-pos">
            <div id="loader-mask"></div>
            <div id="loader-content"></div>
        </div>
    </div>
    <div id="content">


<?php } else {
    ob_start();
    body_class();
    \F\JSONRenderer::getInstance()->bodyClass[] = str_replace(array('class="', '"'), '', ob_get_contents());
    ob_end_clean();

    ob_start();
    \F\JSONRenderer::getInstance()->seoTitle = wp_title('|', false, 'right');
}
