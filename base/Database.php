<?php
function  __autoload($classname)
{
    require_once 'model/'.$classname.'.php';
}

/**
 * trieda sluziaca na pracu s databazou
 *
 * @author yohny
 */
class Database
{
    /* @var $conn mysqli */
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli('localhost','root','Neslira11','adis'); //@ potlaci warning pri neuspechu lebo mame vlastne osetrenie
        $this->conn->set_charset('utf8');
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function getUserByCredentials($login, $password)
    {
        $query = "SELECT * FROM users WHERE login='$login' AND heslo=MD5('$password')";
        /* @var $results mysqli_result */
        $results = $this->conn->query($query);
        if ($results->num_rows == 1)
        {
            $result = $results->fetch_object();
            return new User($result->id, $result->login, $result->kategoria);
        }
        else
            return null;;
    }

    public function getAllFromVelkosti()
    {
        $query = "SELECT * FROM velkosti ORDER BY nazov";
        /* @var $results mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Velkost($result->id, $result->sirka, $result->vyska, $result->nazov);
            $objects[] = $object;
        }
        return $objects;
    }

    public function getAllFromKategorie()
    {
        $query = "SELECT * FROM kategorie ORDER BY nazov";
        /* @var $results mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Kategoria($result->id, $result->nazov);
            $objects[] = $object;
        }
        return $objects;
    }

    public function getBanneryByUser(User $user)
    {
        $query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id)";
        if($user->kategoria!='admin')
                $query.= " WHERE user=$user->id";
        $query.= " ORDER BY velkosti.nazov";
        /* @var $results mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Banner($result->id, $result->user, new Velkost($result->velkost, $result->sirka, $result->vyska, $result->nazov), $result->path);
            $objects[] = $object;
        }
        return $objects;
    }

    public function getReklamyByUser(User $user)
    {
        $query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id)";
        if($user->kategoria!='admin')
                $query.= " WHERE user=$user->id";
        $query.= " ORDER BY velkosti.nazov";
        /* @var $results mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Reklama($result->id, $result->user, new Velkost($result->velkost, $result->sirka, $result->vyska, $result->nazov), $result->meno);
            $objects[] = $object;
        }
        return $objects;
    }

    public function  getKlikyByUser(User $user, Filter $filter, $countOnly = false)
    {
        $query = "SELECT kliky.*, reklamy.meno, bannery.path, u1.login AS zobra_login, u2.login AS inzer_login FROM kliky
            LEFT JOIN reklamy ON (kliky.reklama=reklamy.id)
            LEFT JOIN bannery ON (kliky.banner=bannery.id)
            LEFT JOIN users  AS u1 ON (kliky.zobra=u1.id)
            LEFT JOIN users AS u2 ON (kliky.inzer=u2.id)";
        if($user->kategoria != "admin")  //ak sa nejedna o admina - kliky len pre daneho usera
            $query .= " WHERE $user->kategoria=$user->id"; //$user->kategoria je zaroven nazov stlpca
        else // admin - vsetky kliky
            $query .= " WHERE 1";
        //filter
        if($filter->date!='all')
            $query .= " AND DATE(cas)='$filter->date'";
        if($filter->banner!='all')
            $query .= " AND bannery.id=$filter->banner";
        if($filter->reklama!='all')
            $query .= " AND reklamy.id=$filter->reklama";

        if($countOnly) //len pocet zaznamov
        {
            $countQuery = preg_replace('/(select) (.*) (from kliky)/i', '$1 COUNT(*) AS count $3', $query);  //non-case-sensitive /i, 'from kliky' aby nenahradilo aj v subquery
            /* @var $result mysqli_result */
            $result = $this->conn->query($countQuery);
            return $result->fetch_object()->count;
        }       

        $query .= " ORDER BY cas DESC";
        $query .= " LIMIT ".($filter->page-1)*$filter->rowsPerPage.", $filter->rowsPerPage";

        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Klik($result->id, $result->cas, $result->zobra, $result->zobra_login, $result->reklama, $result->meno, $result->inzer, $result->inzer_login, $result->banner, $result->path);
            $objects[] = $object;
        }
        return $objects;
    }

}
?>
