<?php
/**
 * reprezentuje jeden statisticky zaznam zobrazovany beznemu pouzivatelovi
 * ! nie 'admin', tym sa zobrazuju kliky resp zobrazenia !
 *
 * @author yohny
 */
class Statistika {
    public $cas;
    public $banrekId;
    public $meno;
    public $zobrazenia;
    public $kliky;

    public function __construct($cas,$banrekId,$meno,$zobrazenia,$kliky)
    {
        $this->cas = $cas;
        $this->banrekId = $banrekId;
        $this->meno = $meno;
        $this->zobrazenia = $zobrazenia;
        $this->kliky = $kliky;
    }

    public function __toString()
    {
        return date_format(new DateTime($this->cas), 'd.m.Y');
    }
}
?>
