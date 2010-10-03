<?php
function  __autoload($classname)
{
    require_once $classname.'.php';
}
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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

    public function getAllFromVelkosti()
    {
        $query = "SELECT * FROM velkosti ORDER BY nazov ASC";
        /* @var $result mysqli_result */
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
        $query = "SELECT * FROM kategorie ORDER BY nazov ASC";
        /* @var $result mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Kategoria($result->id, $result->nazov);
            $objects[] = $object;
        }
        return $objects;
    }

     public function getBanneryByUser($login)
    {
        $query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id) WHERE user=(SELECT id FROM users WHERE login='$login') ORDER BY nazov";
        /* @var $result mysqli_result */
        $results = $this->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Banner($result->id, $result->user, new Velkost($result->velkost, $result->sirka, $result->vyska, $result->nazov), $result->path);
            $objects[] = $object;
        }
        return $objects;
    }

}
?>
