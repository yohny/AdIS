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

    public function __construct($rowsPerPage)
    {
        $this->date = date('Y-m-d', time());
        $this->page = 1;
        $this->banner = 'all';
        $this->reklama = 'all';
        $this->rowsPerPage = $rowsPerPage;
        $odDay = 0;
        $odMonth = 0;
        $odYear = 0;
        $doDay = 0;
        $doMonth = 0;
        $doYear = 0;
    }
}
?>
