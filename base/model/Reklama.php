<?php

/**
 * trieda reprezentujuca jeden zaznam z tabulky REKLAMY
 *
 * @author yohny
 */
class Reklama
{

    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * FK na pouzivatela vlastniaceho reklamu
     * @var int
     */
    public $userId;
    /**
     * objekt velkosti reklamy
     * @var Velkost
     */
    public $velkost;
    /**
     * meno reklamy (stlpec v tabulke sa vola 'meno')
     * @var string
     */
    public $name;

    public function __construct($id, $userId, Velkost $velkost, $name)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->velkost = $velkost;
        $this->name = $name;
    }

    public function getKategorie(mysqli $conn)
    {
        $query = "SELECT kategorie.* FROM kategoria_reklama JOIN kategorie ON (kategoria_reklama.kategoria=kategorie.id)
            WHERE reklama=$this->id ORDER BY kategorie.nazov ASC";
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
        $db->conn->autocommit(false); //zacne transaction
        $query = "INSERT INTO reklamy VALUES(NULL, $this->userId, {$this->velkost->id}, '$this->name')";
        if (!$db->conn->query($query))
        {
            $db->conn->rollback();
            return false;
        }
        $this->id = $db->conn->insert_id;
        foreach ($kategorie as $kat)
        {
            $query = "INSERT INTO kategoria_reklama VALUES (NULL, $kat, $this->id)";
            if (!$db->conn->query($query))
            {
                $db->conn->rollback();
                return false;
            }
        }
        $db->conn->commit();
        return true;
    }

    public function delete(Database $db)
    {
        $db->conn->autocommit(false);
        if (!$db->conn->query("DELETE FROM kategoria_reklama WHERE reklama=$this->id") ||
                !$db->conn->query("DELETE FROM reklamy WHERE id=$this->id"))
        {
            $db->conn->rollback();
            return false;
        }
        $db->conn->commit();
        return true;
    }

    public function __toString()
    {
        return $this->name;
    }

}

?>
