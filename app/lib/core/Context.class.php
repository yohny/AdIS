<?php
/**
 * trieda reprezentuje kontext aktualnej poziadavky, singleton
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Context
{
    /**
     * current request instance
     * @var Request
     */
    private $request = null;

    /**
     * current response instance
     * @var Response
     */
    private $response = null;

    /**
     * current database instance
     * @var Request
     */
    private $database = null;

    /**
     * holds instance of this class
     * @var Context
     */
    private static $instance = null;

    private function __construct()
    {
        $this->request = new Request($_SERVER['REQUEST_URI']);
        $this->response = new Response('Ad-IS');
    }

    /**
     * singleton call
     * @return Context
     */
    public static function getInstance()
    {
        if(!self::$instance)
            self::$instance = new Context();
        return self::$instance;
    }

    /**
     * returns actual request object
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * returns actual response object
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * returns actual database object
     * @return Database
     */
    public function getDatabase()
    {
        if($this->database==null)
            $this->database = new Database();
        return $this->database;
    }

    /**
     * vrati aktualne prihlaseneho pouzivatela
     * ak nie je prihlaseny vrati null
     * @return User
     */
    public function getUser()
    {
        if(isset($_SESSION['user']))
            return $_SESSION['user'];
        else
            return null;
    }

    /**
     * vrati unikatny token na obranu formularov proti CSRF
     * @return string
     */
    public function getCsrfToken()
    {
        if(!isset($_SESSION['csrf_token']))
            $_SESSION['csrf_token'] = uniqid();
        return $_SESSION['csrf_token'];
    }
}
?>
