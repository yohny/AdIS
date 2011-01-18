<?php
/**
 * parent class for Klik a Zobrazenie
 *
 * @author yohny
 */
class Event
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

    public function __construct($id, $cas, $zobraId, $reklamaId, $inzerId, $bannerId)
    {
        $this->id = $id;
        $this->cas = $cas;
        $this->zobraId = $zobraId;
        $this->reklamaId = $reklamaId;
        $this->inzerId = $inzerId;
        $this->bannerId = $bannerId;
    }

    public function save(Database $db)
    {
        throw new Exception('Nutne implementovat metodu');
    }

    public function __toString()
    {
        return date_format(new DateTime($this->cas), 'd.m.Y H:i:s');
    }
}
?>
