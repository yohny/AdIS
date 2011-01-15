<?php
/**
 * reprezentuje kontext aktualnej poziadavky
 *
 * @author yohny
 */
class Context
{
    private $request = null;
    private $response = null;
    private $database = null;
    private static $instance = null;

    private function __construct()
    {
        $this->request = new Request(isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'/');
        $this->response = new Response('Ad-IS');
    }

    /**
     * singleton call
     * @return Context
     */
    public static function getInstance()
    {
        if(self::$instance==null)
            self::$instance = new Context ();
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
     * returns database object
     * @return Database
     */
    public function getDatabase()
    {
        if($this->database==null)
            $this->database = new Database ();
        return $this->database;
    }

    public function setUser(User $user)
    {
        $_SESSION['user'] = $user;
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
}
?>
