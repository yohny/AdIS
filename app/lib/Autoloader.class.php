<?php
/**
 * trieda zabezpecujuca autoloading tried aplikacie, singleton
 *
 * @version    1.0
 * @package    AdIS
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Autoloader
{
    /**
     * holds instance of this class
     * @var Autoloader
     */
    private static $instance = null;

    private function __construct()
    {
        spl_autoload_register(null,false); //reset autoloaderov
        spl_autoload_extensions('.class.php');
    }

    /**
     * private singleton call
     * @return Autoloader
     */
    private static function getInstance()
    {
        if(!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * includes class file, or fails with FALSE
     * @param string $path relative path from Autoloader directory
     * @return bool
     */
    private function loadClass($path)
    {
        if(file_exists(__DIR__.DIRECTORY_SEPARATOR.$path))
            require_once $path;
        else
            return false;
    }

    /**
     * registers all classes and libraries
     */
    public static function registerAll()
    {
        self::registerCore();
        self::registerModel();
        self::registerCaptcha();
        self::registerPChart();
    }

    /**
     * registers autoload function for Core classes
     */
    public static function registerCore()
    {
        $callback = array(self::getInstance(),'loadCore');
        if(!spl_autoload_functions() || !in_array($callback, spl_autoload_functions()))
            spl_autoload_register($callback);
    }
    private function loadCore($className)
    {
        $path ='core'.DIRECTORY_SEPARATOR.$className.'.class.php';
        return $this->loadClass($path);
    }

    /**
     * registers autoload function for Model classes
     */
    public static function registerModel()
    {
        $callback = array(self::getInstance(),'loadModel');
        if(!spl_autoload_functions() || !in_array($callback, spl_autoload_functions()))
            spl_autoload_register($callback);
        $callback = array(self::getInstance(),'loadModelBase');
        if(!spl_autoload_functions() || !in_array($callback, spl_autoload_functions()))
            spl_autoload_register($callback);
    }
    private function loadModel($className)
    {
        $path ='model'.DIRECTORY_SEPARATOR.$className.'.class.php';
        return $this->loadClass($path);
    }
    private function loadModelBase($className)
    {
        $path ='model'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.$className.'.class.php';
        return $this->loadClass($path);
    }

    /**
     * registers autoload function for Captcha classes
     */
    public static function registerCaptcha()
    {
        $callback = array(self::getInstance(),'loadCaptcha');
        if(!spl_autoload_functions() || !in_array($callback, spl_autoload_functions()))
            spl_autoload_register($callback);
    }
    private function loadCaptcha($className)
    {
        $path = 'external'.DIRECTORY_SEPARATOR.'captcha'.DIRECTORY_SEPARATOR.$className.'.class.php';
        return $this->loadClass($path);
    }

    /**
     * registers autoload function for pChart classes
     */
    public static function registerPChart()
    {
        $callback = array(self::getInstance(),'loadPChart');
        if(!spl_autoload_functions() || !in_array($callback, spl_autoload_functions()))
            spl_autoload_register($callback);
    }
    private function loadPChart($slassName)
    {
        $path = 'external'.DIRECTORY_SEPARATOR.'pChart'.DIRECTORY_SEPARATOR.$slassName.'.class.php';
        return $this->loadClass($path);
    }
}
?>
