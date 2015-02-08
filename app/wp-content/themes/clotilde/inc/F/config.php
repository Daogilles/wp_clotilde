<?php
define('SEP', DIRECTORY_SEPARATOR);
define('F_PATH', get_template_directory().'/inc/F/');
define('F_PATH_DATA', F_PATH.'data/');
define('PATH_GENERATED', get_template_directory().'/generated/');
define('URL_GENERATED', get_template_directory_uri().'/generated/');

define('PATH_URL', rtrim(str_replace('http://'.$_SERVER['SERVER_NAME'], '', get_site_url()), '/').'/');

if(!defined('IS_PROD')) {
    define('IS_PROD', true);
}
global $table_prefix;
define('DB_PREFIX', $table_prefix);