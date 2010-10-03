<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * trieda reprezentuje jeden zaznam z tabulky VELKOSTI
 *
 * @author yohny
 */
class Velkost
{
    public $id;
    public $sirka;
    public $vyska;
    public $nazov;

    public function __construct($id, $sirka, $vyska, $nazov)
    {
        $this->id = $id;
        $this->sirka = $sirka;
        $this->vyska = $vyska;
        $this->nazov = $nazov;

    }

    public function __toString()
    {
        return $this->nazov;
    }
}
?>
