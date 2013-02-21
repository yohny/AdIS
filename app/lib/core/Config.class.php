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
    private $statRowsPerPage;

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

    /**
     * max allowed inactivity time, when exceeded user is logged out automatically (in sec)
     * @var int
     */
    private $inactivityLimit = 100;


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
                $this->uploadDir = realpath(BASE_DIR.DIRECTORY_SEPARATOR.$config["upload"]["directory"]);
                $this->uploadSize = intval($config["upload"]["max_size"]);
            }
            else
                throw new Exception("Chýbajúce nastavenia uploadu!");
            if(key_exists("session", $config))
            {
                $this->inactivityLimit = intval($config["session"]["inactivityLimit"]);
            }
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
     * vrati host na ktorom bezi DB na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati NULL
     * @return string|NULL
     */
    public static function getDbHost()
    {
        return self::getInstance()->dbHost;
    }

    /**
     * vrati pouzivatelske meno pre pristup do DB na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati NULL
     * @return string|NULL
     */
    public static function getDbUser()
    {
        return self::getInstance()->dbUser;
    }

    /**
     * vrati pouzivatelske heslo pre pristup do DB na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati NULL
     * @return string|NULL
     */
    public static function getDbPassword()
    {
        return self::getInstance()->dbPassw;
    }

    /**
     * vrati meno DB na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati NULL
     * @return string|NULL
     */
    public static function getDbName()
    {
        return self::getInstance()->dbName;
    }

    /**
     * vrati pocet riadkov na stranu zobrazovanych v statistikach na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati 0
     * @return int
     */
    public static function getStatRowsPerPage()
    {
        return self::getInstance()->statRowsPerPage;
    }

    /**
     * vrati adresar na upload bannerov (absolutna cesta) na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati cestu do korenoveho adresara
     * @return string
     */
    public static function getUploadDir()
    {
        return self::getInstance()->uploadDir;
    }

    /**
     * vrati maximalnu povolenu velkost uploadovanych suborov v Bytoch na zaklade nastavenia v config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati 0
     * @return int
     */
    public static function getUploadSize()
    {
        return self::getInstance()->uploadSize;
    }

    /**
     * vrati maximalnu povolenu dobu neaktivity pouzivatela (v sekundach) z config.ini
     * ak toto nastavenie v konfiguracnom subore nie je vrati 0
     * @return int
     */
    public static function getInactivityLimit()
    {
        return self::getInstance()->inactivityLimit;
    }
}
?>
