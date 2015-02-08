<?php
namespace F;

class Loader {
    private static $_instance;

    public static function autoload() {
        if(self::$_instance === null) self::$_instance = new Loader();
        spl_autoload_register(array(self::$_instance,'_try'));
    }

    public function _try($className) {
        $exploded = explode('\\', $className);
        if(count($exploded) > 1 && $exploded[0] === __NAMESPACE__) {
            array_shift($exploded);
            $classPath = join(SEP, $exploded);
            $file = F_PATH.'classes'.SEP.$classPath.'.php';
            if($exploded[0] === 'data') {
                array_shift($exploded);
                $classPath = join(SEP, $exploded);
                $file = F_PATH_DATA.$classPath.'.php';
            }
            if(file_exists($file)) {
                include_once $file;
            } else {
                var_dump('file not exist '.$file);
            }
        }
    }

}
?>
