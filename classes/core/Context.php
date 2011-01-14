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
    /**
     * aktualne prihlaseny pouzivatel alebo null
     * @var User
     */
    private $flash = null;
    private $user = null;
    private $database = null;
    private static $instance = null;

    private function __construct()
    {
        $this->request = new Request(isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL']:'/');
        $this->response = new Response('Ad-IS');
        //$this->database = new Database(); -loadnuta len ak treba SEE $this->getDatabase
        if(isset($_SESSION['user']))
            $this->user = $_SESSION['user'];
        if(isset($_SESSION['flash']))
            $this->flash = $_SESSION['flash'];
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
        //$this->user = $user;
        $_SESSION['user'] = $user;
    }

    /**
     * vrati aktualne prihlaseneho pouzivatela
     * ak nie je prihlaseny vrati null
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setFlash($flash)
    {
        //$this->flah = $flash;
        $_SESSION['flash'] = $flash;
    }

    /**
     * echoes temporary message
     */
    public function putFlash()
    {
        if($this->flash)
        {
            echo "<div class=\"flash\">$this->flash</div>";
            session_unregister('flash');
            $this->flash = null;
        }
    }
}
?>
