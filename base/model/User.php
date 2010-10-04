<?php
/**
 * triedda reprezentujuca jeden zaznam y tabulky USERS (ciastocne),
 * v session reprezentuje aktualne prihlaseneho pouzivatela
 *
 * @author yohny
 */
class User
{
    public $id;
    public $login;
    //public $password;
    //public $web;
    public $kategoria;

    public function __construct($id, $login, $kategoria)
    {
        $this->id = $id;
        $this->login = $login;
        $this->kategoria = $kategoria;
    }

    public function setPassword($old, $new)
    {
        //to be implemented
    }

    public function setWeb()
    {
        //to be implemented
    }

    public function __toString()
    {
        return $this->login;
    }
}
?>
