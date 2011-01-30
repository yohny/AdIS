<?php
/**
 * reprezentuje jeden statisticky zaznam zobrazovany beznemu pouzivatelovi
 * ! nie 'admin', tym sa zobrazuju kliky resp zobrazenia !
 *
 * @author yohny
 */
class Statistika
{
    public $cas;
    public $banrekId;
    public $meno;
    public $zobrazenia;
    public $kliky;

    public function __construct($cas, $banrekId, $meno, $zobrazenia, $kliky)
    {
        $this->cas = $cas;
        $this->banrekId = $banrekId;
        $this->meno = $meno;
        $this->zobrazenia = $zobrazenia;
        $this->kliky = $kliky;
    }

    public function __toString()
    {
        $dateTime = new DateTime($this->cas);
        if(setlocale(LC_ALL, "sk_SK.utf8"))
            return strftime('%A %e. %B %Y', $dateTime->format('U'));
        elseif(setlocale(LC_ALL, "sk_SK")) //iso-8859-2
            return iconv('iso-8859-2', 'utf-8', strftime('%A %e. %B %Y', $dateTime->format('U')));
        else
            return $dateTime->format('d.m.Y');
    }
}
?>
