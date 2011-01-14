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
    public static $options = array(
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

}

?>
