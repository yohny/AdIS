<?php
/**
 * trieda reprezentujuca zaznam z tabulky KLIKY
 *
 * @author yohny
 */
class Zobrazenie
{
    public $id;
    public $cas;
    public $zobraId;
    public $zobraLogin;
    public $reklamaId;
    public $reklamaName;
    public $inzerId;
    public $inzerLogin;
    public $bannerId;
    public $bannerFilename;

    public function __construct($id, $zobraId, $reklamaId, $inzerId, $bannerId)
    {
        $this->id = $id;
        $this->zobraId = $zobraId;
        $this->reklamaId = $reklamaId;
        $this->inzerId = $inzerId;
        $this->bannerId = $bannerId;
    }

    public function __toString()
    {
        return date_format(new DateTime($this->cas), 'd.m.Y H:i:s');
    }
}
?>
