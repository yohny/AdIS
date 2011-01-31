<?php
spl_autoload_register(null,false);//reset autoloaderov
spl_autoload_extensions('.class.php, .php');//pripony
define('CLASSES_DIR', dirname(__FILE__));

/**
 * trieda sluziaca na autoloading ostatnych tried
 *
 * @author yohny
 */
class Autoloader
{
    private static function loadClass($path)
    {
        if(file_exists($path))
            require_once $path;
        else
            return false;
    }

    public static function loadCore($slassName)
    {
        $path = CLASSES_DIR.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
        return self::loadClass($path);
    }

    public static function loadModel($slassName)
    {
        $path = CLASSES_DIR.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
        if(!self::loadClass($path))//ak nezbehne skusi /base
        {
            $path = CLASSES_DIR.DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
            return self::loadClass($path);
        }
    }

    public static function loadCaptcha($slassName)
    {
        $path = self::$baseDir.DIRECTORY_SEPARATOR.'captcha'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
        return self::loadClass($path);
    }

    public static function loadPChart($slassName)
    {
        $path = CLASSES_DIR.DIRECTORY_SEPARATOR.'pChart'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
        return self::loadClass($path);
    }
}
?>
