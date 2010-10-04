<?php
/**
 * trieda reprezentujuca jeden zaznam z tabulky REKLAMY
 *
 * @author yohny
 */
class Reklama
{
    public $id;
    public $userId;
    public $velkost;
    public $name; //meno in table

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

    public function __toString()
    {
        return $this->name;
    }
}
?>
