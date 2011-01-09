<?php

/**
 * trieda reprezentujuca jeden zaznam z tabulky BANNERY
 *
 * @author yohny
 */
class Banner
{

    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * FK na pouzivatela vlastniaceho banner
     * @var int
     */
    public $userId;
    /**
     * objekt velkosti banneru
     * @var Velkost
     */
    public $velkost;
    /**
     * nazov suboru banneru bez cesty (stlpec v tabulke sa vola 'path')
     * @var string
     */
    public $filename; //path in table

    public function __construct($id, $userId, Velkost $velkost, $filename)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->velkost = $velkost;
        $this->filename = $filename;
    }

    public function getKategorie(mysqli $conn)
    {
        $query = "SELECT kategorie.* FROM kategoria_banner JOIN kategorie ON (kategoria_banner.kategoria=kategorie.id)
            WHERE banner=$this->id ORDER BY kategorie.nazov ASC";
        /* @var $result mysqli_result */
        $results = $conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Kategoria($result->id, $result->nazov);
            $objects[] = $object;
        }
        return $objects;
    }

    public function save($kategorie, Database $db)
    {
        $db->conn->autocommit(false); //zacne transactiu
        $query = "INSERT INTO bannery VALUES(NULL, $this->userId, {$this->velkost->id}, '$this->filename')";
        if (!$db->conn->query($query))
        {
            $db->conn->rollback();
            return false;
        }
        $this->id = $db->conn->insert_id;
        foreach ($kategorie as $kat)
        {
            $query = "INSERT INTO kategoria_banner VALUES (NULL, $kat, $this->id)";
            if (!$db->conn->query($query))
            {
                $db->conn->rollback();
                return false;
            }
        }
        $db->conn->autocommit(true);
        return true;
    }

    public function delete(Database $db)
    {
        if (!unlink('../upload/' . $this->filename)) //relat cesta voci zmaz.php, kde je tato metoda volana
            return false;
        $db->conn->autocommit(false);
        if (!$db->conn->query("DELETE FROM kategoria_banner WHERE banner=$this->id") ||
                !$db->conn->query("DELETE FROM bannery WHERE id=$this->id"))
        {
            $db->conn->rollback();
            return false;
        }
        $db->conn->commit();
        return true;
    }

    public function __toString()
    {
        return $this->filename;
    }

}

?>
