<?php
/**
 * reprezentuje odpoved
 *
 * @author yohny
 */
class Response {

    private $title = 'Ad-IS';
    private $nadpis = 'Ad-IS';
    private $resourcces = array();
    public $content;

    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * nastavi nadpis a upravi title okna
     * @param string $title
     */
    public function setHeading($title)
    {
        $this->title .= ' > '.$title;
        $this->nadpis = $title;
    }

    public function getHeading()
    {
        return $this->nadpis;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addResource($resource)
    {
        $this->resourcces[] = $resource;
    }

    public function getResources()
    {
        return $this->resourcces;
    }

    public function  __toString()
    {
        return $this->content;
    }
}
?>
