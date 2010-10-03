<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Banner
 *
 * @author yohny
 */
class Banner
{
    public $id;
    public $userId;
    public $velkost; //for Velkost object
    public $filename; /// path in table

    public function __construct($id, $user, $velkost, $filename)
    {
        $this->id = $id;
        $this->user = $user;
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

    public function __toString()
    {
        return $this->filename;
    }
}
?>
