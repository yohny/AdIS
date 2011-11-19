<?php
/**
 * reprezentuje jeden statisticky zaznam zobrazovany beznemu pouzivatelovi
 *
 * neplati pre kategoriu 'admin', tym sa zobrazuju priamo kliky resp zobrazenia, plati pre ostatnych
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
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
