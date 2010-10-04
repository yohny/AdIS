<?php
/**
 * Description of Klik
 *
 * @author yohny
 */
class Klik
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

    public function __construct($id, $cas, $zobraId, $zobraLogin, $reklamaId, $reklamaName, $inzerId, $inzerLogin, $bannerId, $bannerFilename)
    {
        $this->id = $id;
        $this->cas = $cas;
        $this->zobraId = $zobraId;
        $this->zobraLogin = $zobraLogin;
        $this->reklamaId = $reklamaId;
        $this->reklamaName = $reklamaName;
        $this->inzerId = $inzerId;
        $this->inzerLogin = $inzerLogin;
        $this->bannerId = $bannerId;
        $this->bannerFilename = $bannerFilename;
    }

    public function __toString()
    {
        return date_format(new DateTime($this->cas), 'd.m.Y H:i:s');
    }
}
?>
