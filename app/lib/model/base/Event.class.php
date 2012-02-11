<?php
/**
 * spolocny predok pre triedy Klik a Zobrazenie
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
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

    public function __construct($id, DateTime $cas, $zobraId, $reklamaId, $inzerId, $bannerId)
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
        return $this->cas->format('d.m.Y H:i:s');
    }
}
?>
