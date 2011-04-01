<?php
/**
 * trieda starajuca sa o konfiguraciu
 * poskytuje konfiguracne nastavenia aplikacie
 *
 * @author yohny
 */
class Config
{
    private static $instance = null;
    private $dbHost = '#host';
    private $dbUser = '#user';
    private $dbPassw = '#password';
    private $dbName = '#name';
    private $statRowsPerPage = 10;
    private $uploadDir = './pload';


    private function __construct()
    {
        libxml_use_internal_errors(); //aby parser neohlasoval chyby XML dokumentu

        //musi byt absolutna lebo niekedy cita konfig voci index.php, inokedy voci /img abo /distrib
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
            if(isset($xml->stat_rows_per_page) && is_numeric(trim($xml->stat_rows_per_page)))
                $this->statRowsPerPage = trim($xml->stat_rows_per_page);
            if(isset($xml->upload_dir))
                $this->uploadDir = trim($xml->upload_dir);
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
            self::$instance = new Config();
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
     * @return string
     */
    public static function getStatRowsPerPage()
    {
        return self::getInstance()->statRowsPerPage;
    }

    /**
     * vrati adresar na upload bannerov (absolutna cesta) na zaklade nastavenia v config.xml
     * ak toto nastavenie v konfiguracnom subore nie je vrati './upload' (default)
     * @return string
     */
    public static function getUploadDir()
    {
        return realpath(dirname(__FILE__).'/../../../'.self::getInstance()->uploadDir).DIRECTORY_SEPARATOR;
    }

    /**
     * vrati korenovy adresar - s index.php (absolutna cesta) bez / na konci
     * @return string
     */
    public static function getBaseDir()
    {
        return realpath(dirname(__FILE__).'/../../../');
    }
}
?>
