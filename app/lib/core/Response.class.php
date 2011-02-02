<?php
/**
 * reprezentuje odpoved
 *
 * @author yohny
 */
class Response {

    private $title = 'Ad-IS';
    private $nadpis = 'Ad-IS';
    private $headerContentType = 'text/html';
    private $encoding = 'utf-8';
    private $resourcces = array();
    public $content;
    public $error = false;
    /**
     * ak stranka robi redirect tak obsahuje ciel redirectu
     * @var string
     */
    public $redirect = null;

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


    public function setFlash($flash)
    {
        $_SESSION['flash'] = $flash;
    }

    /**
     * echoes temporary message
     */
    public function getFlash()
    {
        if(isset($_SESSION['flash']))
        {
            $resp = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $resp;
        }
        else
            return null;
    }

    public function setHeaderContentType($contentType)
    {
        $this->headerContentType = $contentType;
    }

    public function getHeaderContentType()
    {
        return "$this->headerContentType; charset=$this->encoding";
    }


    public function  __toString()
    {
        return $this->content?$this->content:'';
    }
}
?>
