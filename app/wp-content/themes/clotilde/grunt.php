<?php
define('WP_USE_THEMES', false);
require_once(dirname(__FILE__).'/../../../wp-load.php');

include_once(dirname(__FILE__).'/inc/F/config.php');
include_once(dirname(__FILE__).'/inc/F/Loader.php');
\F\Loader::autoload();
include_once(dirname(__FILE__).'/inc/F/project.php');

$task = !empty($_REQUEST['task']) ? $_REQUEST['task'] : 'compress';
$json = array();

$pathURL = get_template_directory_uri().'/';
switch($task) {
    case 'compress' :



        $scriptsJSON = array();
        $stylesJSON = array();

        foreach(\F\AssetManager::getInstance()->SCRIPTS_GROUP as $groupName => $scripts) {
            if(empty($scriptsJSON[$groupName])) $scriptsJSON[$groupName] = array();
            foreach($scripts as $script) {
                if($script['canMin'] === true) {
                    $scriptsJSON[$groupName][] = str_replace($pathURL, '', $script['src']);
                }
            }
        }

        foreach(\F\AssetManager::getInstance()->STYLES_GROUP as $groupName => $styles) {
            if(empty($stylesJSON[$groupName])) $stylesJSON[$groupName] = array();
            foreach($styles as $style) {
                if($style['canMin'] === true) {
                    $stylesJSON[$groupName][] = str_replace($pathURL, '', $style['src']);
                }
            }
        }
        
        $json = array(
            'scripts' => $scriptsJSON,
            'styles' => $stylesJSON
        );

        break;

    case 'bdd' :
        if(!empty($_REQUEST['tablename'])) {
            global $wpdb;

            \F\DB::getInstance()->query('SELECT * FROM '.DB_PREFIX.$_REQUEST['tablename']);
            $names = \F\DB::getInstance()->wpdb->get_col_info('name', -1);
            $types = \F\DB::getInstance()->wpdb->get_col_info('type', -1);
            $primary = \F\DB::getInstance()->wpdb->get_col_info('primary_key', -1);
            $not_null = \F\DB::getInstance()->wpdb->get_col_info('not_null', -1);

            $fields = array();

            foreach($names as $index => $name) {
                $fields[] = array(
                    'Field' => $name,
                    'Type' => $types[$index],
                    'Null' => $not_null[$index]== 1 ? "NO" : "YES",
                    'Key' => $primary[$index] == 1 ? "PRI" : '',
                    'Default' => null,
                    'Extra' => $primary[$index] == 1 ? "auto_increment" : '' //oui je sais... mais fucking wordpress à pas l'air de fournir l'info //http://codex.wordpress.org/Class_Reference/wpdb#Getting_Column_Information
                );
            }

            if(!empty($fields)) {
                $json = $fields;
            } else {
                $json = array('error');
            }

        }
        break;
}

echo json_encode($json);

die();