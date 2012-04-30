<?php
/**
 * trieda starajuca sa o konfiguraciu
 * poskytuje konfiguracne nastavenia aplikacie, ktore nacita z config.ini
 * @see /app/config.ini
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
    private $dbHost;

    /**
     * database user
     * @var string
     */
    private $dbUser;

    /**
     * database password
     * @var string
     */
    private $dbPassw;

    /**
     * database name
     * @var string
     */
    private $dbName;

    /**
     * number of rows showed per page in statistics listings
     * @var int
     */
    private $statRowsPerPage = 10;

    /**
     * directory for banner upload
     * @var string
     */
    private $uploadDir;

    /**
     * max allowed size of uploaded files (B)
     * @var int
     */
    private $uploadSize;


    private function __construct()
    {
        if($config = parse_ini_file(BASE_DIR."/app/config.ini", true))
        {
            if(key_exists("database", $config))
            {
                $this->dbHost = $config["database"]["server"];
                $this->dbName = $config["database"]["name"];
                $this->dbUser = $config["database"]["user"];
                $this->dbPassw = $config["database"]["password"];
            }
            else
                throw new Exception("Chýbajúce nastavenia databázy!");
            if(key_exists("view", $config))
            {
                $this->statRowsPerPage = intval($config["view"]["statistics_rows"]);
            }
            if(key_exists("upload", $config))
            {
                $this->uploadDir = $config["upload"]["directory"];
                $this->uploadSize = intval($config["upload"]["max_size"]);
            }
            else
                throw new Exception("Chýbajúce nastavenia uploadu!");
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
     * vrati adresar na upload bannerov (absolutna cesta) na zaklade nastavenia v config.xml
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
}
?>
