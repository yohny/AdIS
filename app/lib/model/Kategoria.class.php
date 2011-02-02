<?php
/**
 * trieda reprezentuje jeden zaznam z tabulky KATEGORIE
 *
 * @author yohny
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
