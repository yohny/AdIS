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
        if ($db->conn->query($query)->fetch_object()->count==1)
        {
            $query = "UPDATE users SET heslo=MD5('$new') WHERE id=$this->id";
            return $db->conn->query($query);
        }
        else
            return false;
    }

    public function getWeb(Database $db)
    {
        $query = "SELECT web FROM users WHERE id=$this->id";
        return $db->conn->query($query)->fetch_object()->web;
    }

    public function setWeb($web, Database $db)
    {
        if(!$stm = $db->conn->prepare("UPDATE users SET web=? WHERE id=$this->id"))
                return false;
        $stm->bind_param('s', $web);
        $ret = $stm->execute();
        $stm->close();
        return $ret;
    }

    public function hasReklamaOfSize(Velkost $velkost, Database $db)
    {
        $query = "SELECT COUNT(*) AS count FROM reklamy WHERE user=$this->id AND velkost=$velkost->id";
        if($db->conn->query($query)->fetch_object()->count>0)
          return true;
        else
          return false;
    }

    public function hasBannerOfSize(Velkost $velkost, Database $db)
    {
        $query = "SELECT COUNT(*) AS count FROM bannery WHERE user=$this->id AND velkost=$velkost->id";
        if($db->conn->query($query)->fetch_object()->count>0)
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
     * @return bool
     */
    public static function create($login, $password, $web, $group, Database $db)
    {
        if(!$stm = $db->conn->prepare("INSERT INTO users VALUES(NULL, ?, MD5(?), ? , ?)"))
            return false;
        $stm->bind_param('ssss', $login,$password,$web,$group);
        $ret = $stm->execute();
        $stm->close();
        return $ret;
    }

    /**
     * overi ci sa dany login uz nepouziva
     * @param string $login
     * @param Database $db
     * @return bool
     */
    public static function isLoginUnique($login, Database $db)
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
    }
}
?>
