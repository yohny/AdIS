<?php
/**
 * filtovacie kriteria pre zobrazenie statistiky
 *
 * @author yohny
 */
class Filter
{
    public $date;
    public $page;
    public $banner;
    public $reklama;
    public $rowsPerPage;
    public $odDay;
    public $odMonth;
    public $odYear;
    public $doDay;
    public $doMonth;
    public $doYear;
    public $type;
    public static $dateOptions = array(
        'today' => 'dnes',
        'week' => 'posledný týždeň',
        'month' => 'posledný mesiac',
        'year' => 'posledný rok',
        'all' => 'komplet',
        'custom' => 'vlastné obdobie');

    public function __construct($rowsPerPage)
    {
        $this->date = 'today';
        $this->page = 1;
        $this->banner = 'all';
        $this->reklama = 'all';
        $this->rowsPerPage = $rowsPerPage;
        $this->odDay = 0;
        $this->odMonth = 0;
        $this->odYear = 0;
        $this->doDay = 0;
        $this->doMonth = 0;
        $this->doYear = 0;
        $this->type = 'click';
    }

    /**
     * ziska hodnoty filtra zo vstupneho pola
     * @param array $filretData
     * @return bool
     */
    public function parse($filterData)
    {
        if (isset($filterData['page']))
        {
            if (!is_numeric($filterData['page']))
                return false;
            else
                $this->page = $filterData['page'];
        }
        if (isset($filterData['date']))
        {
            if (!key_exists($filterData['date'], self::$dateOptions))
                return false;
            else
            {
                $this->date = $filterData['date'];
                if ($this->date == 'custom')
                {
                    if(!isset($filterData['odDay']) || !isset($filterData['odMonth']) || !isset($filterData['odYear'])
                    || !isset($filterData['doDay']) || !isset($filterData['doMonth']) || !isset($filterData['doYear'])
                    || !is_numeric($filterData['odDay']) || !is_numeric($filterData['odMonth']) || !is_numeric($filterData['odYear'])
                    || !is_numeric($filterData['doDay']) || !is_numeric($filterData['doMonth']) || !is_numeric($filterData['doYear']))
                        return false;
                    if($filterData['odDay']<1 || $filterData['odDay']>31
                    || $filterData['doDay']<1 || $filterData['doDay']>31
                    || $filterData['odMonth']<1 || $filterData['odMonth']>12
                    || $filterData['doMonth']<1 || $filterData['doMonth']>12
                    || $filterData['odYear']<2010 || $filterData['odYear']>date('Y')
                    || $filterData['doYear']<2010 || $filterData['doYear']>date('Y'))
                        return false;
                    $this->odDay = $filterData['odDay'];
                    $this->odMonth = $filterData['odMonth'];
                    $this->odYear = $filterData['odYear'];
                    $this->doDay = $filterData['doDay'];
                    $this->doMonth = $filterData['doMonth'];
                    $this->doYear = $filterData['doYear'];
                }
            }
        }
        if (isset($filterData['bann']))
        {
            if(!is_numeric($filterData['bann']) && $filterData['bann']!='all' && $filterData['bann']!='del')
                return false;
            else
                $this->banner = $filterData['bann'];
        }
        if (isset($filterData['rekl']))
        {
            if(!is_numeric($filterData['rekl']) && $filterData['rekl']!='all' && $filterData['rekl']!='del')
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
