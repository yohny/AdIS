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
        $this->conn = new mysqli('localhost','root','Neslira11','adis');
        //mysql_query("SET CHARACTER SET utf8");
        //mysql_query("SET NAMES 'utf8'")
        //bool mysql_set_charset( string $charset [, resource $link_identifier]) is the preferred way 
        //to change the charset. Using mysql_query() to execute SET NAMES .. (SET CHARACTER SET ..) is not recommended.
        $this->conn->set_charset('utf8');
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function isLoginUnique($login)
    {
        $query = "SELECT COUNT(*) AS count FROM users WHERE login='$login'";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if($result->fetch_object()->count>0)
          return false;
        else
          return true;
    }

    public function addUser($login, $password, $web, $group)
    {
        $query = "INSERT INTO users VALUES(NULL, '$login', MD5('$heslo'), '$web' , '$kategoria')";
        return $this->conn->query($query);
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
            return null;
    }

    public function getWebById($id)
    {
        $query = "SELECT web FROM users WHERE id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if(!$result)
          return false;
        else
          return $result->fetch_object()->web;
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

    public function getBannerById($id)
    {
        $query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id) WHERE bannery.id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if(!$result || $result->num_rows!=1)
            return null;
        $object = $result->fetch_object();
        return new Banner($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->path);
    }

    public function getBannerForReklama(Reklama $reklama)
    {
        $query ="SELECT DISTINCT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov
            FROM bannery
            JOIN velkosti ON (bannery.velkost=velkosti.id)
            JOIN kategoria_banner ON (bannery.id=kategoria_banner.banner)
            WHERE bannery.velkost={$reklama->velkost->id}
            AND kategoria_banner.kategoria IN (SELECT kategoria FROM kategoria_reklama WHERE reklama=$reklama->id)
            ORDER BY RAND() LIMIT 1";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if(!$result || $result->num_rows!=1)
            return null;
        $object = $result->fetch_object();
        return new Banner($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->path);
    }

    public function deleteBanner(Banner $banner)
    {
        unlink('../upload/'.$banner->filename);
        $this->conn->autocommit(false);
        $this->conn->query("DELETE FROM bannery WHERE id=$banner->id");
        $this->conn->query("DELETE FROM kategoria_banner WHERE banner=$banner->id");
        $this->conn->autocommit(true);
        return $this->conn->commit();
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

    public function getReklamaById($id)
    {
        $query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id) WHERE reklamy.id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if(!$result || $result->num_rows!=1)
            return null;
        $object = $result->fetch_object();
        return new Reklama($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->meno);
    }

    public function deleteReklama(Reklama $reklama)
    {
        $this->conn->autocommit(false);
        $this->conn->query("DELETE FROM reklamy WHERE id=$reklama->id");
        $this->conn->query("DELETE FROM kategoria_reklama WHERE reklama=$reklama->id");
        $this->conn->autocommit(true);
        return $this->conn->commit();
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
        {
            //FIXME datetime OOP requires php 5.3.0+, not owrking on olders versions
            $date = new DateTime();
            switch ($filter->date)
            {
                case 'today':
                    break;
                case 'week':
                    $date->sub(new DateInterval('P7D'));
                    break;
                case 'month':
                    $date->sub(new DateInterval('P1M'));
                    break;
                case 'year':
                    $date->sub(new DateInterval('P1Y'));
                    break;
            }
            if($filter->date!='custom')
                $query .= " AND DATE(cas)>='{$date->format('Y-m-d')}'";
            else //custom
            {
                $from = new DateTime($filter->odYear.'-'.$filter->odMonth.'-'.$filter->odDay);
                $to = new DateTime($filter->doYear.'-'.$filter->doMonth.'-'.$filter->doDay);
                $query .= " AND DATE(cas) BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'";
            }
        }
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
            $object = new Klik($result->zobra, $result->reklama, $result->inzer, $result->banner);
            $object->id = $result->id;
            $object->cas = $result->cas;
            $object->zobraLogin = $result->zobra_login;
            $object->reklamaName = $result->meno;
            $object->inzerLogin = $result->inzer_login;
            $object->bannerFilename = $result->path;
            $objects[] = $object;
        }
        return $objects;
    }

    public function saveKlik(Klik $klik)
    {
        $query = "INSERT INTO kliky VALUES(NULL, NOW(), $klik->zobraId, $klik->inzerId, $klik->reklamaId, $klik->bannerId)";
        return $this->conn->query($query);
    }
}
?>
