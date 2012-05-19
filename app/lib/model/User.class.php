<?php
/**
 * trieda reprezentujuca jeden zaznam z tabulky USERS (ciastocne),
 * v session reprezentuje aktualne prihlaseneho pouzivatela
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class User
{
    const ROLE_INZER = "inzer";
    const ROLE_ZOBRA = "zobra";
    const ROLE_ADMIN = "admin";

    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * jedinecne prihlasovacie meno
     * @var string
     */
    private $login;
    //private $password;
    private $web;
    /**
     * kategoria pouzivatela: 'zobra', 'inzer' alebo 'admin'
     * @var string
     */
    public $kategoria;
    /**
     * cas registracie pouzivatela
     * @var DateTime
     */
    private $regTime;
    /**
     * cas posledneho prihlasenia
     * @var DateTime
     */
    private $loginTime;

    public function __construct($id, $login, $web, $kategoria, DateTime $registrationTime, DateTime $lastLoginTime)
    {
        $this->id = $id;
        $this->login = $login;
        $this->web = $web;
        $this->kategoria = $kategoria;
        $this->regTime = $registrationTime;
        $this->loginTime = $lastLoginTime;
    }

    public function setPassword($old, $new)
    {
        $db = Context::getInstance()->getDatabase();
        if (!$stm = $db->prepare("SELECT COUNT(*) FROM users WHERE id=? AND heslo=MD5(?)"))
            return false;
        $stm->bind_param('is', $this->id, $old);
        if (!$stm->execute())
            return false;
        $stm->bind_result($count);
        $stm->fetch();
        $stm->close();
        if ($count != 1)
            return false;
        if (!$stm = $db->prepare("UPDATE users SET heslo=MD5(?) WHERE id=?"))
            return false;
        $stm->bind_param('si', $new, $this->id);
        $ret = $stm->execute();
        $stm->close();
        return $ret;
    }

    public function setWeb($web)
    {
        $db = Context::getInstance()->getDatabase();
        if (!$stm = $db->prepare("UPDATE users SET web=? WHERE id=?"))
            return false;
        $stm->bind_param('si', $web, $this->id);
        if($ret = $stm->execute())
            $this->web = $web;
        $stm->close();
        return $ret;
    }

    public function setLoginTimeNow()
    {
        $db = Context::getInstance()->getDatabase();
        $query = "UPDATE users SET last_login=NOW() WHERE id=$this->id";
        return $db->query($query);
    }

    public function getWeb()
    {
        return $this->web;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getRegistrationTime()
    {
        return $this->regTime;
    }

    public function getLastLoginTime()
    {
        return $this->loginTime;
    }


    public function hasReklamaOfSize(Velkost $velkost)
    {
        if($this->kategoria!='zobra')
            throw new Exception ('Zlá kategória používateľa');
        $db = Context::getInstance()->getDatabase();
        $query = "SELECT COUNT(*) AS count FROM reklamy WHERE user=$this->id AND velkost=$velkost->id";
        if ($db->query($query)->fetch_object()->count > 0)
            return true;
        else
            return false;
    }

    public function hasBannerOfSize(Velkost $velkost)
    {
        if($this->kategoria!='inzer')
            throw new Exception ('Zlá kategória používateľa');
        $db = Context::getInstance()->getDatabase();
        $query = "SELECT COUNT(*) AS count FROM bannery WHERE user=$this->id AND velkost=$velkost->id";
        if ($db->query($query)->fetch_object()->count > 0)
            return true;
        else
            return false;
    }

    public function __toString()
    {
        return htmlspecialchars($this->login);
    }

    /**
     * vytvori noveho pouzivatela v DB
     * @param string $login
     * @param string $password
     * @param string $web
     * @param string $group 'inze' | 'zobra' | 'admin'
     * @return bool
     */
    public static function create($login, $password, $web, $group)
    {
        $db = Context::getInstance()->getDatabase();
        if (!$stm = $db->prepare("INSERT INTO users VALUES(NULL, ?, MD5(?), ? , ?, NOW(), DEFAULT)"))
            return false;
        $stm->bind_param('ssss', $login, $password, $web, $group);
        $ret = $stm->execute();
        $stm->close();
        return $ret;
    }

    /**
     * overi ci sa dany login uz nepouziva
     * @param string $login
     * @return bool
     */
    public static function isLoginUnique($login)
    {
        $db = Context::getInstance()->getDatabase();
        if (!$stm = $db->prepare("SELECT COUNT(*) FROM users WHERE login LIKE?"))
            throw new Exception('DB error');
        $stm->bind_param('s', $login);
        //aj tu je mozne nastavit hodnotu $login, k samotnemu bindu dojde az v execute
        //$login='fero';
        if (!$stm->execute())
            throw new Exception('DB error');
        //tu je mozne zmenit hodnotu $login a nanovo vykonat - vhodne pri viacnasobnych INSERToch
        $stm->bind_result($count); //musi byt po execute a pred fetch, da sa rebindnut ale treba potom refetchnut
        if (!$stm->fetch())
            throw new Exception('DB error');
        $stm->close();
        if ($count == 0)
            return true;
        else
            return false;
    }

    /**
     * overi ci sa dany web uz nepouziva
     * @param string $web
     * @return bool
     */
    public static function isWebUnique($web)
    {
        $db = Context::getInstance()->getDatabase();
        if (!$stm = $db->prepare("SELECT COUNT(*) FROM users WHERE web LIKE ?"))
            throw new Exception('DB error');
        $stm->bind_param('s', $web);
        if (!$stm->execute())
            throw new Exception('DB error');
        $stm->bind_result($count);
        if (!$stm->fetch())
            throw new Exception('DB error');
        $stm->close();
        if ($count == 0)
            return true;
        else
            return false;
    }

    /**
     * overi platnost url
     * @param string $url
     * @return bool
     */
    public static function validUrl($url)
    {
        if(preg_match('/^([a-z\-]+\.)?[a-z_\-]+\.[a-z]{2,3}$/', $url))
            return true;
        else
            return false;
    }

    /**
     * validates form input
     * @param array $input
     * @return string validation output message, empty if valid
     */
    public static function validateInput($input)
    {
        $message = '';
        //login
        if (!preg_match('/^[a-zA-Z\d]{4,10}$/', $input['login']))
            $message = "Neplatný login!(len 4 až 10 znakov: a-z, A-Z, 0-9)<br/>";
        elseif (!self::isLoginUnique($input['login']))
            $message .= "Váš login NIE JE unikátny, zvoľte iný.<br/>";
        //heslo
        if ($input['heslo'] != $input['heslo2'])
            $message .= "Nezhodujúce sa heslá!<br/>";
        elseif(!preg_match('/^[^\'\"]{4,10}$/', $input['heslo']))
            $message .= "Neplatné heslo! (nesmie obsahovať &#039; ani &quot;)<br/>";
        //web
        if (!self::validUrl($input['web']))
            $message .= "Neplatná webová adresa!<br/>";
        elseif(!self::isWebUnique($input['web']))
            $message .= "Tento web už bol zaregistrovaný.<br/>";
        //kategoria
        if(!in_array($input['skupina'], array(User::ROLE_INZER, User::ROLE_ZOBRA)))
            $message .= "Neplatná použivateľská skupina.<br/>";

        return $message;
    }
}
?>
