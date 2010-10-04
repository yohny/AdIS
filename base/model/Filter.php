<?php
/**
 * Description of Filter
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

    public function __construct($rowsPerPage)
    {
        $this->date = date('Y-m-d', time());
        $this->page = 1;
        $this->banner = 'all';
        $this->reklama = 'all';
        $this->rowsPerPage = $rowsPerPage;
    }
}
?>
