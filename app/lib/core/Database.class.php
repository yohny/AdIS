<?php
/**
 * trieda reprezentujuca databazu
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Database extends mysqli
{
    private $userBaseSelect = "SELECT id, login, web, kategoria, registered, last_login FROM users";
    private $velkostiBaseSelect = "SELECT * FROM velkosti";
    private $kategorieBaseSelect = "SELECT * FROM kategorie";
    private $banneryBaseSelect = "SELECT bannery.*, sirka, vyska, nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id)";
    private $reklamyBaseSelect = "SELECT reklamy.*, sirka, vyska, nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id)";
    const USER = 1;
    const VELKOST = 2;
    const KATEGORIA = 3;
    const BANNER = 4;
    const REKLAMA = 5;

    public function __construct()
    {
        @parent::__construct(Config::getDbHost() , Config::getDbUser(), Config::getDbPassword(), Config::getDbName());
        if (mysqli_connect_errno())
            throw new Exception('Nepodarilo sa pripojiť na databázu!');
        $this->set_charset('utf8');
        $now = new DateTime();
        $this->query("SET time_zone = '{$now->format('P')}'");
    }

    public function __destruct()
    {
        $this->close();
    }


    /**
     * vrati objekt pouzivatela na zaklade prihlasovacich udajov
     * @param string $login
     * @param string $password
     * @return User
     */
    public function  getUserByCredentials($login, $password)
    {
        if (!$stm = $this->prepare($this->userBaseSelect." WHERE login=? AND heslo=MD5(?)"))
            return null;
        $stm->bind_param('ss', $login, $password);
        if (!$stm->execute())
            return null;
        $stm->bind_result($id, $login, $web, $kateg, $regTime, $loginTime);
        $ret = $stm->fetch();
        $stm->close();
        if ($ret)
            return new User($id, $login, $web, $kateg, new DateTime($regTime), new DateTime($loginTime));
        else
            return null;
    }

    /**
     * vrati objekt pouzivatela (zobra) na zaklade ulr adresy
     * !prehladava len ZOBRAZOVATELOV!
     * @param string $referer url adresa
     * @return User
     */
    public function getUserByReferer($referer)
    {
        $referer = preg_replace('/^http:\/\/([^\/]+).*$/', '$1', $referer);
        if (!$stm = $this->prepare($this->userBaseSelect." WHERE web LIKE ? AND kategoria='zobra'"))
            return null;
        $stm->bind_param('s', $referer);
        if (!$stm->execute())
            return null;
        $stm->bind_result($id, $login, $web, $kateg, $regTime, $loginTime);
        $ret = $stm->fetch();
        $stm->close();
        if ($ret)
            return new User($id, $login, $web, $kateg, new DateTime($regTime), new DateTime($loginTime));
        else
            return null;
    }

    /**
     * vrati pouzivatela na zaklade primarneho kluca
     * @param int $id primarny kluc
     * @return User
     */
    public function getUserByPK($id)
    {
        $query = $this->userBaseSelect." WHERE  id=$id";
        $result = $this->query($query);
        return $this->resultsetToModel($result, self::USER, false);
    }

    /**
     * vrati vsetky zaznamy z tabulky VELKOSTI
     * @return Velkost array
     */
    public function getAllFromVelkosti()
    {
        $query = $this->velkostiBaseSelect." ORDER BY nazov";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::VELKOST);
    }

    /**
     * vrati velkost na zaklade primarneho kluca
     * @param int $id primarny kluc
     * @return Velkost
     */
    public function getVelkostByPK($id)
    {
        $query = $this->velkostiBaseSelect." WHERE id=$id";
        $result = $this->query($query);
        return $this->resultsetToModel($result, self::VELKOST, false);
    }

    /**
     * vrati vsetky zaznamy z tabulky KATEGORIE
     * @return Kategoria array
     */
    public function getAllFromKategorie()
    {
        $query = $this->kategorieBaseSelect." ORDER BY nazov";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::KATEGORIA);
    }

    /**
     * vrati vsetky bannery
     *
     * @return Banner array
     */
    public function getAllFromBannery()
    {
        $query = $this->banneryBaseSelect." ORDER BY bannery.id";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::BANNER);
    }

    /**
     * vrati vsetky bannery daneho pouzivatela
     *
     * @param User $user
     * @return Banner array
     */
    public function getBanneryByUser(User $user)
    {
        if($user->kategoria!='inzer')
            throw new Exception ("Zlá kategória používateľa ($user->kategoria)");
        $query = $this->banneryBaseSelect." WHERE user=$user->id";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::BANNER);
    }

    /**
     * vrati banner na zaklade primarneho kluca
     * @param int $id primarny kluc
     * @return Banner
     */
    public function getBannerByPK($id)
    {
        $query = $this->banneryBaseSelect." WHERE bannery.id=$id";
        $result = $this->query($query);
        return $this->resultsetToModel($result, self::BANNER, false);
    }

    /**
     * vrati nahodny banner splnajuci kriteria na zobrazenie v danej reklame
     * @param Reklama $reklama reklamna jednotka, pre ktoru treba vybrat banner
     * @return Banner
     */
    public function getRandBannerForReklama(Reklama $reklama)
    {
        $query = "SELECT DISTINCT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov
            FROM bannery
            JOIN velkosti ON (bannery.velkost=velkosti.id)
            JOIN kategoria_banner ON (bannery.id=kategoria_banner.banner)
            WHERE bannery.velkost={$reklama->velkost->id}
            AND kategoria_banner.kategoria IN (SELECT kategoria FROM kategoria_reklama WHERE reklama=$reklama->id)
            ORDER BY RAND() LIMIT 1";
        $result = $this->query($query);
        return $this->resultsetToModel($result, self::BANNER, false);
    }

    /**
     * vrati vsetky reklamy
     *
     * @return Reklama array
     */
    public function getAllFromReklamy()
    {
        $query = $this->reklamyBaseSelect." ORDER BY reklamy.id";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::REKLAMA);
    }

    /**
     * vrati vsetky reklamy daneho pouzivatela,
     *
     * @param User $user
     * @return Reklama array
     */
    public function getReklamyByUser(User $user)
    {
        if($user->kategoria!='zobra')
            throw new Exception ("Zlá kategória používateľa ($user->kategoria)");
        $query = $this->reklamyBaseSelect." WHERE user=$user->id";
        $results = $this->query($query);
        return $this->resultsetToModel($results, self::REKLAMA);
    }

    /**
     * vrati reklamu na zaklade primarneho kluca
     * @param int $id primarny kluc
     * @return Reklama
     */
    public function getReklamaByPK($id)
    {
        $query = $this->reklamyBaseSelect." WHERE reklamy.id=$id";
        /* @var $result mysqli_result */
        $result = $this->query($query);
        return $this->resultsetToModel($result, self::REKLAMA, false);
    }

    /**
     * varti statistiky pre daneho pouzivatela a kriteria
     * @param User $user pouzivatel, pre  ktoreho sa vytiahnu statistiky
     * @param Filter $filter specifikuje vyberove kriteria
     * @param type $countOnly ak true vrati len pocet
     * @return Statistika|int ak $countOnly je true vrati pocet, inak pole statistik
     */
    public function getStatisticsForUser(User $user, Filter $filter, $countOnly = false)
    {
        if($user->kategoria!='zobra' && $user->kategoria!='inzer')
            throw new Exception ("Zlá kategória používateľa ($user->kategoria)");

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

        $cond = "";//podmienka pri zvolenom banneri/reklame - default 'all' = ziadna podmienka
        if ($filter->banner == 'del' || $filter->reklama == 'del') //len zmazane reklamy/bannery
            $cond = " AND $banrek NOT IN (SELECT id FROM $table WHERE user=$user->id)";
        elseif ($filter->banner != 'all' || $filter->reklama != 'all') //zvolena konkretna reklama/banner
            $cond = " AND $banrek = " . ($user->kategoria == 'inzer' ? $filter->banner : $filter->reklama);

        $query = "SELECT day, IFNULL(views.count, 0) AS views, IFNULL(clicks.count, 0) AS clicks FROM days
            LEFT JOIN
            (SELECT DATE(cas) AS den, COUNT(*) AS count FROM zobrazenia WHERE $user->kategoria = $user->id $cond GROUP BY den) views
            ON (days.day=views.den)
            LEFT JOIN
            (SELECT DATE(cas) AS den, COUNT(*) AS count FROM kliky WHERE $user->kategoria = $user->id $cond GROUP BY den) clicks
            ON (days.day=clicks.den)
            WHERE day>='{$user->regTime->format('Y-m-d')}'"; //len od datumu registracie

        if ($filter->date != 'all')
        {
            $date = new DateTime();
            switch ($filter->date)
            {
                case 'today':
                    break;
                case 'week':
                    //$date->sub(new DateInterval('P7D'));
                    $date->modify('-7 days');
                    break;
                case 'month':
                    //$date->sub(new DateInterval('P1M'));
                    $date->modify('-1 month');
                    break;
                case 'year':
                    //$date->sub(new DateInterval('P1Y'));
                    $date->modify('-1 year');
                    break;
            }
            if ($filter->date != 'custom')
                $query .= " AND day>='{$date->format('Y-m-d')}'";
            else //custom
                $query .= " AND day BETWEEN '{$filter->from->format('Y-m-d')}' AND '{$filter->to->format('Y-m-d')}'";
        }

        if ($countOnly) //len pocet zaznamov
        {
            $result = $this->query("SELECT COUNT(*) as count, SUM(views) AS views, SUM(clicks) AS clicks FROM ($query) stat");
            /* @var $result mysqli_result */
            $object = $result->fetch_object();
            return array('count' => $object->count, 'views' => $object->views, 'clicks' => $object->clicks);
        }

        $query .= " ORDER BY day DESC";
        $query .= " LIMIT " . ($filter->page - 1) * Config::getStatRowsPerPage() . ", " . Config::getStatRowsPerPage();
        $results = $this->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Statistika(new DateTime($result->day), $result->views, $result->clicks);
            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * vracia statistiky pre admina (podla filtra bud Kliky alebo Zobrazenia)
     * @param Filter $filter specifikuje vyberove kriteria
     * @param type $countOnly ak true vrati len pocet
     * @return Event|int ak $countOnly=true tak len pocet, inak pole Eventov
     */
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
            $date = new DateTime();
            switch ($filter->date)
            {
                case 'today':
                    break;
                case 'week':
                    //$date->sub(new DateInterval('P7D'));
                    $date->modify('-7 days');
                    break;
                case 'month':
                    //$date->sub(new DateInterval('P1M'));
                    $date->modify('-1 month');
                    break;
                case 'year':
                    //$date->sub(new DateInterval('P1Y'));
                    $date->modify('-1 year');
                    break;
            }
            if ($filter->date != 'custom')
                $query .= " AND DATE(cas)>='{$date->format('Y-m-d')}'";
            else //custom
                $query .= " AND DATE(cas) BETWEEN '{$filter->from->format('Y-m-d')}' AND '{$filter->to->format('Y-m-d')}'";
        }

        if ($filter->banner == 'del')
            $query .= " AND banner NOT IN (SELECT id FROM bannery)";
        elseif ($filter->banner != 'all')
            $query .= " AND banner=$filter->banner";

        if ($filter->reklama == 'del')
            $query .= " AND reklama NOT IN (SELECT id FROM reklamy)";
        elseif ($filter->reklama != 'all')
            $query .= " AND reklama=$filter->reklama";

        if ($countOnly) //len pocet zaznamov
        {
            $countQuery = preg_replace("/(select) (.*) (from $table)/i", "$1 COUNT(*) AS count $3", $query);  //non-case-sensitive /i, 'from kliky/zobrazenia' aby nenahradilo aj v subquery
            $result = $this->query($countQuery); /* @var $result mysqli_result */
            return $result->fetch_object()->count;
        }

        $query .= " ORDER BY cas DESC";
        $query .= " LIMIT " . ($filter->page - 1) * Config::getStatRowsPerPage() . ", " . $filter->rowsPerPage;

        $results = $this->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Event($result->id, $result->cas,$result->zobra, $result->reklama, $result->inzer, $result->banner);
            $object->zobraLogin = $result->zobra_login;
            $object->reklamaName = $result->meno;
            $object->inzerLogin = $result->inzer_login;
            $object->bannerFilename = $result->path;
            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * vrati zobrazenie na zaklade primarneho kluca
     * @param int $id primarny kluc
     * @return Zobrazenie
     */
    public function getZobrazenieByPK($id)
    {
        $query = "SELECT * FROM zobrazenia WHERE id=$id";
        /* @var $result mysqli_result */
        $result = $this->query($query);
        if (!$result || $result->num_rows != 1)
            return null;
        $object = $result->fetch_object();
        return new Zobrazenie($object->id, $object->cas, $object->zobra, $object->reklama, $object->inzer, $object->banner, $object->clicked);
    }

    /**
     * pretransformuje MySQL resultset na objekty pozadovaneho typu
     * @param mysqli_result $resultset result query na databazu
     * @param int $model urcuje triedu modelu, na ktorej instancie sa ma previest resultset
     * @param bool $asArray ak false vrati jediny objekt inak vracia pole objektov
     */
    private function resultsetToModel(mysqli_result $resultset, $model, $asArray = true)
    {
        if(!$resultset)
            throw new Exception("Databázová chyba: Prázdny resultset");
        $objects = array();
        while ($object = $resultset->fetch_object())
        {
            switch ($model)
            {
                case self::USER:
                    $objects[] = new User($object->id, $object->login, $object->web, $object->kategoria, new DateTime($object->registered), new DateTime($object->last_login));
                    break;
                case self::VELKOST:
                    $objects[] = new Velkost($object->id, $object->sirka, $object->vyska, $object->nazov);
                    break;
                case self::KATEGORIA:
                    $objects[] = new Kategoria($object->id, $object->nazov);
                    break;
                case self::BANNER:
                    $objects[] = new Banner($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->path);
                    break;
                case self::REKLAMA:
                    $objects[] = new Reklama($object->id, $object->user, new Velkost($object->velkost, $object->sirka, $object->vyska, $object->nazov), $object->meno);
                    break;
                default :
                    throw new Exception("Nepodporovaná trieda modelu");
                    break;
            }
        }
        if(!$asArray)
            return count($objects)!=1?null:$objects[0];
        else
            return $objects;
    }
}
?>
