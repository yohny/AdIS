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
        $query = "INSERT INTO reklamy VALUES(NULL, $this->userId, {$this->velkost->id}, '$this->name')";
        if(!$db->conn->query($query))
            return false;
        $this->id = $db->conn->insert_id;
        $db->conn->autocommit(false);
        foreach ($kategorie as $kat)
            $db->conn->query("INSERT INTO kategoria_reklama VALUES (NULL, $kat, $this->id)");
        $db->conn->autocommit(true);
        return $db->conn->commit();
    }

    public function delete(Database $db)
    {
        $db->conn->autocommit(false);
        $db->conn->query("DELETE FROM reklamy WHERE id=$this->id");
        $db->conn->query("DELETE FROM kategoria_reklama WHERE reklama=$this->id");
        $db->conn->autocommit(true);
        return $db->conn->commit();
    }

    public function __toString()
    {
        return $this->name;
    }
}
?>
