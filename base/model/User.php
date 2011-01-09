<?php
/**
 * triedda reprezentujuca jeden zaznam z tabulky USERS (ciastocne),
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

    public function setPassword($old, $new, Database $db)
    {
        $query = "SELECT COUNT(*) AS count FROM users WHERE id=$this->id AND heslo=MD5('$old')";
        /* @var $result mysqli_result */
        $result = $db->conn->query($query);
        if ($result->fetch_object()->count==1)
        {
            $query = "UPDATE users SET heslo=MD5('$new') WHERE id=$this->id";
            $db->conn->query($query);
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

    /**
     * vytvori noveho pouzivatela v DB
     * @param string $login
     * @param string $password
     * @param string $web
     * @param string $group 'inze' | 'zobra' [| 'admin']
     * @param Database $db
     * @return <type>
     */
    public static function create($login, $password, $web, $group, Database $db)
    {
        if(!$stm = $db->conn->prepare("INSERT INTO users VALUES(NULL, ?, MD5(?), ? , ?)"))
            return false;
        $stm->bind_param("ssss", $login,$password,$web,$group);
        $ret = $stm->execute();
        $stm->close();
        return $ret;
    }

    /**
     * overi ci sa dany login uz nepouziva
     * @param string $login
     * @param Database $db
     * @return <type>
     */
    public static function isLoginUnique($login, Database $db) //FIXME: prehodit na prepared statementy, for security reasons
    {
        $stm = $db->conn->prepare("SELECT COUNT(*) FROM users WHERE login=?");
        $stm->bind_param('s', $login);
        //aj tu je mozne nastavit hodnotu $login, k samotnemu bindu dojde az v execute
        //$login='fero';
        $stm->execute();
        //tu je mozne zmenit hodnotu $login a nanovo vykonat - vhodne pri viacnasobnych INSERToch
        $stm->bind_result($count); //musi byt po execute a pred fetch, da sa rebindnut ale treba potom refetchnut
        $stm->fetch();
        $stm->close();
        if ($count == 0)
            return true;
        else
            return false;
//        $query = "SELECT COUNT(*) AS count FROM users WHERE login=$login";
//        $result = $this->conn->query($query); /* @var $result mysqli_result */
//        if ($result->fetch_object()->count > 0)
//            return false;
//        else
//            return true;
    }
}
?>
