<?php
namespace F;

class Renderer {

    private static $_instance = null;

    private $_inlineScripts = array('footer'=>array(), 'header'=>array());

    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new Renderer();
        }
        return self::$_instance;
    }

    public function __construct() {}

    public function addInlineScript($script, $inFooter = true) {
        $position = $inFooter ? 'footer' : 'header';
        $this->_inlineScripts[$position][] = $script;
    }

    protected function _showScript($namePage, $scripts, $header = false) {
        $position = $header === true ? 'header' : 'footer';
        if(is_array($scripts) && is_array($scripts[$position])) {
            if(IS_PROD && file_exists(DOC_ROOT.'generated/version_js.php')) {
                if(!empty($scripts[$position])) {
                    $cptCantMin = 0;
                    foreach($scripts[$position] as $name => $script) {
                        if(isset($script['canMin']) && $script['canMin'] === false) {
                            $cptCantMin ++;
                            echo '<script src="'.$script['src'].'"></script>';
                        }
                    }
                    if($cptCantMin < count($scripts[$position])) {
                        include(DOC_ROOT.'generated/version_js.php');
                        /* @var $CURRENT_VERSION string */
                        echo '<script src="'.PATH_URL.'generated/'.$namePage.'_'.$position.'_'.$CURRENT_VERSION.'.js"></script>';
                    }
                }
            } else {
                foreach($scripts[$position] as $name => $script) {
                    echo '<script src="'.$script['src'].'"></script>';
                }
            }
        }
    }

    public function showStyles() {
        $stylesGroup = AssetManager::getInstance()->getStyles();
        $isProd = IS_PROD && file_exists(PATH_GENERATED.'version_css.php');
        if($isProd) {
            include(PATH_GENERATED.'version_css.php');
            /* @var $CURRENT_VERSION string */
        }
        foreach($stylesGroup as $group => $styles) {
            if($isProd) {
                if(!empty($styles)) {
                    $cptCantMin = 0;
                    foreach($styles as $style) {
                        if(isset($style['canMin']) && $style['canMin'] === false) {
                            $cptCantMin++;
                            echo '<link rel="stylesheet" href="'.$style['src'].'" />';
                        }
                    }
                    if($cptCantMin < count($styles)) {
                        echo '<link rel="stylesheet" href="'.URL_GENERATED.$group.'_'.$CURRENT_VERSION.'.css" />';
                    }
                }
            } else {
                if(!empty($styles)) { foreach($styles as $style) {
                    echo '<link rel="stylesheet" href="'.$style['src'].'" />';
                }}
            }
        }
    }

    /**
     * @param boolean $header
     */
    public function showScripts($header = false) {
        $position = $header ? 'header' : 'footer';
        $scriptsGroup = AssetManager::getInstance()->getScript($header);
        $isProd = IS_PROD && file_exists(PATH_GENERATED.'version_js.php');
        if($isProd) {
            include(PATH_GENERATED.'version_js.php');
            /* @var $CURRENT_VERSION string */
        }
        foreach($scriptsGroup as $group => $scripts) {
            if($isProd) {
                if(!empty($scripts)) {
                    $cptCantMin = 0;
                    foreach($scripts as $script) {
                        if(isset($script['canMin']) && $script['canMin'] === false) {
                            $cptCantMin++;
                            echo '<script src="'.$script['src'].'" ></script>';
                        }
                    }
                    if($cptCantMin < count($scripts)) {
                        echo '<script src="'.URL_GENERATED.$group.'_'.$CURRENT_VERSION.'.js" '.(!$header ? 'async' : '').' ></script>';
                    }
                }
            } else {
                if(!empty($scripts)) { foreach($scripts as $script) {
                    echo '<script src="'.$script['src'].'" ></script>';
                }}
            }
        }
        if(is_array($this->_inlineScripts) && !empty($this->_inlineScripts[$position])) {
            echo '<script>';
            echo implode(';', $this->_inlineScripts[$position]);
            echo '</script>';
        }
    }

}

?>
