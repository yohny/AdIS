<?php
/**
 * trieda starajuca sa o konfiguraciu
 * poskytuje konfiguracne nastavenia aplikacie, ktore nacita z config.xml
 * @see /app/config.xml
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Config
{
    /**
     * static, instance of this class
     * @var Config
     */
    private static $instance = null;

    /**
     * database host (server)
     * @var string
     */
    private $dbHost = '#host';

    /**
     * database user
     * @var string
     */
    private $dbUser = '#user';

    /**
     * database password
     * @var string
     */
    private $dbPassw = '#password';

    /**
     * database name
     * @var string
     */
    private $dbName = '#name';

    /**
     * number of rows showed per page in statistics listings
     * @var int
     */
    private $statRowsPerPage = 10;

    /**
     * directory for banner upload
     * @var string
     */
    private $uploadDir = './upload';

    /**
     * max allowed size of uploaded files (B)
     * @var int
     */
    private $uploadSize = 20000;


    private function __construct()
    {
        libxml_use_internal_errors(); //aby parser neohlasoval chyby XML dokumentu

        //musi byt absolutna lebo niekedy cita konfig voci index.php, inokedy voci /img alebo /distrib
        if($xml = @simplexml_load_file(realpath(dirname(__FILE__).'/../../config.xml')))
        {
            if(isset($xml->database_host))
                $this->dbHost = trim($xml->database_host);
            if(isset($xml->database_user))
                $this->dbUser = trim($xml->database_user);
            if(isset($xml->database_password))
                $this->dbPassw = trim($xml->database_password);
            if(isset($xml->database_name))
                $this->dbName = trim($xml->database_name);
            if(isset($xml->stat_rows_per_page) && is_numeric(trim($xml->stat_rows_per_page)) && intval($xml->stat_rows_per_page)>0)
                $this->statRowsPerPage = intval($xml->stat_rows_per_page);
            if(isset($xml->upload_dir) && ($path = realpath(self::getBaseDir().DIRECTORY_SEPARATOR.trim($xml->upload_dir))))
                $this->uploadDir = $path.DIRECTORY_SEPARATOR;
            if(isset($xml->upload_max_filesize) && is_numeric(trim($xml->upload_max_filesize)))
                $this->uploadSize = intval($xml->upload_max_filesize);
        }
        else
            throw new Exception('Nepodarilo sa načitať konfiguráciu!');
    }

    /**
     * singleton call
     * @return Config
     */
    private static function getInstance()
    {
        if(!self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * vrati host na ktorom bezi DB na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati '#host'
     * @return string
     */
    public static function getDbHost()
    {
        return self::getInstance()->dbHost;
    }

    /**
     * vrati pouzivatelske meno pre pristup do DB na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati '#user'
     * @return string
     */
    public static function getDbUser()
    {
        return self::getInstance()->dbUser;
    }

    /**
     * vrati pouzivatelske heslo pre pristup do DB na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati '#password'
     * @return string
     */
    public static function getDbPassword()
    {
        return self::getInstance()->dbPassw;
    }

    /**
     * vrati meno DB na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati '#user'
     * @return string
     */
    public static function getDbName()
    {
        return self::getInstance()->dbName;
    }

    /**
     * vrati pocet riadkov na stranu zobrazovanych v statistikach na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati '10'
     * @return int
     */
    public static function getStatRowsPerPage()
    {
        return self::getInstance()->statRowsPerPage;
    }

    /**
     * vrati adresar na upload bannerov (absolutna cesta) na zaklade nastavenia v config.xml s '/' na konci
     * ak toto nastavenie v konfiguracnom subore nie je vrati cestu do adresara upload/ (default)
     * @return string
     */
    public static function getUploadDir()
    {
        return self::getInstance()->uploadDir;
    }

    /**
     * vrati maximalnu povolenu velkost uploadovanych suborov v Bytoch na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati 20000 (default)
     * @return string
     */
    public static function getUploadSize()
    {
        return self::getInstance()->uploadSize;
    }

    /**
     * vrati korenovy adresar - s index.php (absolutna cesta) bez '/' na konci
     * @return string
     */
    public static function getBaseDir()
    {
        return realpath(dirname(__FILE__).'/../../../');
    }
}
?>
