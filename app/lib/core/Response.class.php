<?php
/**
 * trieda reprezentuje aktualnu odpoved uzivatelovi (http response)
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Response
{
    /**
     * hodnota TITLE tagu
     * @var string
     */
    private $title = 'Ad-IS';

    /**
     * nadpis v main bloku (H3)
     * @var string
     */
    private $nadpis = 'Ad-IS';

    /**
     * hodnota 'Content-type' atributu vratenej hlavicky
     * @var string
     */
    private $headerContentType = 'text/html';

    /**
     * kodovanie stranky odosielane v hlavicke
     * @var string
     */
    private $encoding = 'utf-8';

    /**
     * pole dodatocnych zdrojov (js, css) nalinkovanych na aktualnu stranku
     * @var string array
     */
    private $resourcces = array();

    /**
     * html obsah stranky (main bloku)
     * @var string
     */
    public $content;

    /**
     * flag urcujuci ci stranka je chybova alebo ok
     * @var bool
     */
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
    public function setHeading($heading)
    {
        $this->title .= ' > '.$heading;
        $this->nadpis = $heading;
    }

    public function getHeading()
    {
        return htmlspecialchars($this->nadpis);
    }

    public function getTitle()
    {
        return htmlspecialchars($this->title);
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
            return htmlspecialchars($resp);
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
