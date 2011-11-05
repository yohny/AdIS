<?php
/**
 * spoloscny predok pre klik a Zobrazenie
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
        $dateTime = new DateTime($this->cas);
        if(setlocale(LC_ALL, "sk_SK.utf8"))
            return strftime('%A %e. %B %Y %H:%M:%S', $dateTime->format('U'));
        elseif(setlocale(LC_ALL, "sk_SK")) //iso-8859-2
            return iconv('iso-8859-2', 'utf-8', strftime('%A %e. %B %Y %H:%M:%S', $dateTime->format('U')));
        else
            return $dateTime->format('d.m.Y H:i:s');
    }
}
?>
