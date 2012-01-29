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
    /**
     * datum zaznamu
     * @var DateTime
     */
    public $den;
    /**
     * pocet zobrazeni
     * @var int
     */
    public $zobrazenia;
    /**
     * pocet kliknuti
     * @var int
     */
    public $kliky;

    public function __construct(DateTime $den, $zobrazenia, $kliky)
    {
        $this->den = $den;
        $this->zobrazenia = $zobrazenia;
        $this->kliky = $kliky;
    }

    public function __toString()
    {
            return $this->den->format('d.m.Y');
    }
}
?>
