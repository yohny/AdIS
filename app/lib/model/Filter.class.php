<?php
/**
 * filtrovacie kriteria pre zobrazenie statistiky
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Filter
{
    public $date;
    public $from;//only used for 'custom' date
    public $to;//only used for 'custom' date
    public $page;
    public $banner;
    public $reklama;
    public $type;
    public static $dateOptions = array(
        'today' => 'dnes',
        'week' => 'posledný týždeň',
        'month' => 'posledný mesiac',
        'year' => 'posledný rok',
        'all' => 'komplet',
        'custom' => 'vlastné obdobie');

    /**
     * objekt reprezentujuci pouzivatelske filtrovacie kriteria
     * @param array $postData vstup od pouzivatela
     */
    public function __construct(array $requestData)
    {
        $this->date = 'today';
        $this->from = Context::getInstance()->getUser()->getRegistrationTime();
        $this->to = new DateTime();
        $this->page = 1;
        $this->banner = 'all';
        $this->reklama = 'all';
        $this->type = 'click';
        if(!$this->parse($requestData))
            throw new Exception("Neplatný filter!");
    }

    /**
     * ziska hodnoty filtra zo vstupneho pola
     * @param array $filterData filtrovacie kriteria z formulara
     * @return bool true ak parsovanie bolo uspesne inak false
     */
    private function parse(array $filterData)
    {
        if (isset($filterData['page']))
        {
            if (!ctype_digit($filterData['page']))
                return false;
            else
                $this->page = intval($filterData['page']);
        }
        if (isset($filterData['date']))
        {
            if (!key_exists($filterData['date'], self::$dateOptions))
                return false;
            else
                $this->date = $filterData['date'];

            if ($this->date == 'custom')
            {
                try {
                    $this->from = new DateTime($filterData['odYear'] . '-' . $filterData['odMonth'] . '-' . $filterData['odDay']);
                    $this->to = new DateTime($filterData['doYear'] . '-' . $filterData['doMonth'] . '-' . $filterData['doDay']);
                } catch(Exception $ex) {
                    return  false;
                }
            }
        }
        if (isset($filterData['bann']))
        {
            if(!ctype_digit($filterData['bann']) && $filterData['bann']!='all' && $filterData['bann']!='del')
                return false;
            else
                $this->banner = $filterData['bann'];
        }
        if (isset($filterData['rekl']))
        {
            if(!ctype_digit($filterData['rekl']) && $filterData['rekl']!='all' && $filterData['rekl']!='del')
                return false;
            else
                $this->reklama = $filterData['rekl'];
        }
        if (isset($filterData['type']))
        {
            if($filterData['type']!='click' && $filterData['type']!='view')
                return false;
            else
                $this->type = $filterData['type'];
        }
        return true;
    }
}
?>
