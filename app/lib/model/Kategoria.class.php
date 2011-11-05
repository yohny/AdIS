<?php
/**
 * trieda reprezentuje jeden zaznam z tabulky KATEGORIE
 * 
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Kategoria
{
    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * nazov kategorie reklam/bannerov
     * @var string
     */
    public $nazov;

    public function __construct($id, $nazov)
    {
        $this->id = $id;
        $this->nazov = $nazov;
    }

    public function __toString()
    {
        return htmlspecialchars($this->nazov);
    }
}
?>
