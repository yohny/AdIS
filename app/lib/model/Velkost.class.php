<?php
/**
 * trieda reprezentuje jeden zaznam z tabulky VELKOSTI
 * 
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Velkost
{
    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * sirka reklamy/banneru
     * @var int
     */
    public $sirka;
    /**
     * vyska reklamy/banneru
     * @var int
     */
    public $vyska;
    /**
     * nazov typu reklamy/banneru
     * @var string
     */
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
        return htmlspecialchars($this->nazov);
    }
}
?>
