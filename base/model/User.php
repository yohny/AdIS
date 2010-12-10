<?php
/**
 * triedda reprezentujuca jeden zaznam y tabulky USERS (ciastocne),
 * v session reprezentuje aktualne prihlaseneho pouzivatela
 *
 * @author yohny
 */
class User
{
    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * jedinecne prihlasovacie meno
     * @var string
     */
    public $login;
    //public $password;
    //public $web;
    /**
     * kategoria pouzivatela: 'zobra' alebo 'inzer'
     * @var string
     */
    public $kategoria;

    public function __construct($id, $login, $kategoria)
    {
        $this->id = $id;
        $this->login = $login;
        $this->kategoria = $kategoria;
    }

    public function setPassword(mysqli $conn, $old, $new)
    {
        $query = "SELECT COUNT(*) AS count FROM users WHERE id=$this->id AND heslo=MD5('$old')";
        /* @var $result mysqli_result */
        $result = $conn->query($query);
        if ($result->fetch_object()->count==1)
        {
            $query = "UPDATE users SET heslo=MD5('$new') WHERE id=$this->id";
            $conn->query($query);
            return true;
        }
        else
            return false;
    }

    public function getWeb(mysqli $conn)
    {
        $query = "SELECT web FROM users WHERE id=$this->id";
        /* @var $result mysqli_result */
        $result = $conn->query($query);
        return $result->fetch_object()->web;
    }

    public function setWeb(mysqli $conn, $web)
    {
        if(!filter_var($web, FILTER_VALIDATE_URL))
            return false;

        $query = "UPDATE users SET web='$web' WHERE id=$this->id";
        $conn->query($query);
        return true;
    }

    public function hasReklamaOfSize(Velkost $velkost, Database $db)
    {
        $query = "SELECT COUNT(*) AS count FROM reklamy WHERE user=$this->id AND velkost=$velkost->id";
        /* @var $result mysqli_result */
        $result = $db->conn->query($query);
        if($result->fetch_object()->count>0)
          return true;
        else
          return false;
    }

    public function hasBannerOfSize(Velkost $velkost, Database $db)
    {
        $query = "SELECT COUNT(*) AS count FROM bannery WHERE user=$this->id AND velkost=$velkost->id";
        /* @var $result mysqli_result */
        $result = $db->conn->query($query);
        if($result->fetch_object()->count>0)
          return true;
        else
          return false;
    }

    public function __toString()
    {
        return $this->login;
    }
}
?>
