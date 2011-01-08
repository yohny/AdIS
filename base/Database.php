<?php
function __autoload($classname)
{
    require_once 'model/' . $classname . '.php';
}

/**
 * trieda sluziaca na pracu s databazou
 *
 * @author yohny
 */
class Database
{
    /**
     * actual mysqli connection object
     * @var mysqli
     */
    public $conn;

    public function __construct()
    {
        $this->conn = @new mysqli('localhost', 'root', 'Neslira11', 'adis');
        if (mysqli_connect_errno ())
            throw new Exception('Nepodarilo sa pripojiť na databázu!');
        //mysql_query("SET CHARACTER SET utf8");
        //mysql_query("SET NAMES 'utf8'")
        //bool mysql_set_charset( string $charset [, resource $link_identifier]) - preferred way
        //to change the charset. Using mysql_query() to execute SET NAMES .. (SET CHARACTER SET ..) is not recommended.
        $this->conn->set_charset('utf8');
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function isLoginUnique($login) //FIXME: prehodit na prepared statementy, for security reasons
    {

        if($stm = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE login=?"))
        {
            $stm->bind_param('s', $login);
            //aj tu je mozne nastavit hodnotu $login, k samotnemu bindu dojde az v execute
            //$login='fero';
            $stm->execute();
            //tu je mozne zmenit hodnotu $login a nanovo vykonat - vhodne pri viacnasobnych INSERToch
            $stm->bind_result($count);//musi byt po execute a pred fetch, da sa rebindnut ale treba potom refetchnut
            $stm->fetch();
            $stm->close();
            if($count==0)
                return true;
            else
                return false;
        }
        else
            throw new Exception ('statement failed');

//        $query = "SELECT COUNT(*) AS count FROM users WHERE login=$login";
//        $result = $this->conn->query($query); /* @var $result mysqli_result */
//        if ($result->fetch_object()->count > 0)
//            return false;
//        else
//            return true;
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

    public function getWebByUserId($id)
    {
        $query = "SELECT web FROM users WHERE id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if (!$result)
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

    public function getVelkostByPK($id)
    {
        $query = "SELECT * FROM velkosti WHERE id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if (!$result || $result->num_rows != 1)
            return null;
        $object = $result->fetch_object();
        return new Velkost($object->id, $object->sirka, $object->vyska, $object->nazov);
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
        if ($user->kategoria != 'admin')
            $query.= " WHERE user=$user->id";
        $query.= " ORDER BY user,velkosti.nazov";
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

    public function getBannerByPK($id)
    {
        $query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id) WHERE bannery.id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if (!$result || $result->num_rows != 1)
            return null;
        $object = $result->fetch_object();
        return new Banner($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->path);
    }

    public function getBannerForReklama(Reklama $reklama)
    {
        $query = "SELECT DISTINCT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov
            FROM bannery
            JOIN velkosti ON (bannery.velkost=velkosti.id)
            JOIN kategoria_banner ON (bannery.id=kategoria_banner.banner)
            WHERE bannery.velkost={$reklama->velkost->id}
            AND kategoria_banner.kategoria IN (SELECT kategoria FROM kategoria_reklama WHERE reklama=$reklama->id)
            ORDER BY RAND() LIMIT 1";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if (!$result || $result->num_rows != 1)
            return null;
        $object = $result->fetch_object();
        return new Banner($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->path);
    }

    public function getReklamyByUser(User $user)
    {
        $query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id)";
        if ($user->kategoria != 'admin')
            $query.= " WHERE user=$user->id";
        $query.= " ORDER BY user,velkosti.nazov";
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

    public function getReklamaByPK($id)
    {
        $query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id) WHERE reklamy.id=$id";
        /* @var $result mysqli_result */
        $result = $this->conn->query($query);
        if (!$result || $result->num_rows != 1)
            return null;
        $object = $result->fetch_object();
        return new Reklama($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->meno);
    }

    public function getStatisticsForUser(User $user, Filter $filter, $countOnly = false)
    {
        if ($user->kategoria == 'inzer')
        {
            $banrek = "banner";
            $table = "bannery";
            $colname = "path";
        }
        else //zobra
        {
            $banrek = "reklama";
            $table = "reklamy";
            $colname = "meno";
        }

        $clicksSubQuery = "SELECT DATE(cas) AS cdate, COUNT(*) AS ccount, $banrek AS cbanrek
            FROM kliky
            WHERE $user->kategoria = $user->id
            GROUP BY cdate,cbanrek";
        $viewsSubQuery = "SELECT DATE(cas) AS vdate, COUNT(*) AS vcount, $banrek AS vbanrek
            FROM zobrazenia
            WHERE $user->kategoria = $user->id
            GROUP BY vdate,vbanrek";

        if ($countOnly)
            $selectPart = "COUNT(*) AS count, SUM(vcount) AS vsum, SUM(ccount) AS csum";
        else
            $selectPart = "vdate AS cas, vbanrek AS banrek, $table.$colname AS meno, vcount, ccount";

        $query = " SELECT $selectPart FROM
            ($viewsSubQuery) AS wiews
            LEFT JOIN
            ($clicksSubQuery) AS clicks
            ON (cdate=vdate AND cbanrek=vbanrek)
            LEFT JOIN $table ON ($table.id = vbanrek)";

        if ($filter->date != 'all')
        {
            $date = new DateTime(); // FIXME datetime OOP = phph 5.3.0+ only (konkretne sub nepojde)
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
            if ($filter->date != 'custom')
                $query .= " WHERE vdate>='{$date->format('Y-m-d')}'";
            else //custom
            {
                $from = new DateTime($filter->odYear . '-' . $filter->odMonth . '-' . $filter->odDay);
                $to = new DateTime($filter->doYear . '-' . $filter->doMonth . '-' . $filter->doDay);
                $query .= " WHERE vdate BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'";
            }
        }
        else
            $query .= " WHERE 1";

        if($filter->banner == 'del' || $filter->reklama == 'del') //zmazane reklamy/bannery
            $query .= " AND vbanrek NOT IN (SELECT id FROM $table WHERE user=$user->id)";
        elseif($filter->banner != 'all' || $filter->reklama != 'all') //zvolena reklama/banner
            $query .= " AND vbanrek=".($user->kategoria=='inzer'?$filter->banner:$filter->reklama);

        if ($countOnly) //len pocet zaznamov
        {
            $result = $this->conn->query($query);  /* @var $result mysqli_result */
            $object = $result->fetch_object();
            return array('count' => $object->count, 'views' => $object->vsum, 'clicks' => $object->csum);
        }

        $query .= " ORDER BY cas DESC,meno";
        $query .= " LIMIT " . ($filter->page - 1) * $filter->rowsPerPage . ", $filter->rowsPerPage";

        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Statistika($result->cas, $result->banrek, $result->meno, $result->vcount, $result->ccount);
            $objects[] = $object;
        }
        return $objects;
    }

    public function getStatisticsForAdmin(Filter $filter, $countOnly = false)
    {
        if ($filter->type == 'click')
            $table = 'kliky';
        else //view
            $table = 'zobrazenia';

        $query = "SELECT $table.*, reklamy.meno, bannery.path, u1.login AS zobra_login, u2.login AS inzer_login FROM $table
            LEFT JOIN reklamy ON ($table.reklama=reklamy.id)
            LEFT JOIN bannery ON ($table.banner=bannery.id)
            LEFT JOIN users  AS u1 ON ($table.zobra=u1.id)
            LEFT JOIN users AS u2 ON ($table.inzer=u2.id)";
        $query .= " WHERE 1";
        //filter
        if ($filter->date != 'all')
        {
            //FIXME datetime OOP requires php 5.3.0+, not working on olders versions
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
            if ($filter->date != 'custom')
                $query .= " AND DATE(cas)>='{$date->format('Y-m-d')}'";
            else //custom
            {
                $from = new DateTime($filter->odYear . '-' . $filter->odMonth . '-' . $filter->odDay);
                $to = new DateTime($filter->doYear . '-' . $filter->doMonth . '-' . $filter->doDay);
                $query .= " AND DATE(cas) BETWEEN '{$from->format('Y-m-d')}' AND '{$to->format('Y-m-d')}'";
            }
        }

        if($filter->banner == 'del')
            $query .= " AND banner NOT IN (SELECT id FROM bannery)";
        elseif ($filter->banner != 'all')
            $query .= " AND banner=$filter->banner";

        if($filter->reklama == 'del')
            $query .= " AND reklama NOT IN (SELECT id FROM reklamy)";
        elseif ($filter->reklama != 'all')
            $query .= " AND reklama=$filter->reklama";

        if ($countOnly) //len pocet zaznamov
        {
            $countQuery = preg_replace("/(select) (.*) (from $table)/i", "$1 COUNT(*) AS count $3", $query);  //non-case-sensitive /i, 'from kliky/zobrazenia' aby nenahradilo aj v subquery
            $result = $this->conn->query($countQuery); /* @var $result mysqli_result */
            return $result->fetch_object()->count;
        }

        $query .= " ORDER BY cas DESC";
        $query .= " LIMIT " . ($filter->page - 1) * $filter->rowsPerPage . ", $filter->rowsPerPage";

        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Event($result->id, $result->zobra, $result->reklama, $result->inzer, $result->banner);
            $object->cas = $result->cas;
            $object->zobraLogin = $result->zobra_login;
            $object->reklamaName = $result->meno;
            $object->inzerLogin = $result->inzer_login;
            $object->bannerFilename = $result->path;
            $objects[] = $object;
        }
        return $objects;
    }
}
?>
