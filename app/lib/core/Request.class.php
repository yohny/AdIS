<?php
/**
 * trieda reprezentuje aktualnu poziadavku uzivatela (http request)
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 * @todo improve uri parsing for template/notemplate decision based on processing script ditectory (path start like actions/), not path ending as it is now
 */
class Request
{
    /**
     * adresa pozadovana uzivatelom (request_uri) bez query stringu
     * @var string
     */
    private $uri = '';

    /**
     * pole nazvov suborov alebo ciest (bez .php), ktore su bez layoutu (ajax)
     * !po mapingu na fyzicke cesty
     * @var regexp
     */
    private $withoutTemplate = array(
        'checklogin',
        'chpas',
        'chweb');

    /**
     * pole nazvov suborov alebo ciest (bez .php), ktore su pristupne bez lognutia
     * !po mapingu na fyzicke cesty!
     * @var string
     */
    private $public = array(
        'about',
        'faq',
        'registracia',
        'checklogin',
        'klikerror',
        'prihlas',
        'registruj');

    /**
     * flag urcujuci ci je potrebny layout
     * @var bool
     */
    public $hasTemplate = true;

    /**
     * flag urcujuci ci pozadovany subor existuje
     * @var bool
     */
    public $fileExists = true;

    /**
     * flag oznacujuci ci pozadovana stranka je pristuna verejne
     * alebo len prihlasenym userom
     * @var bool
     */
    public $isPublic = false;

    /**
     * nastavi instancne premenne na zaklade spracovania request_uri
     * @param string $req_url pozadovana adresa (url) aj s query stringom
     */
    public function __construct($req_uri)
    {
        $this->uri = preg_replace("/\?.*$/", "", $req_uri); //odstrani query string
        if ($this->uri == '/')//index
            $this->uri = TEMPLATES_DIR.'/content/about';
        else
        {
            //maping logickych url adries na fyzicke cesty (relativne voci index.php)
            $this->uri = preg_replace("/^\/(\w+)$/", TEMPLATES_DIR."/content/$1", $this->uri);
            $this->uri = preg_replace("/^\/action\/(\w+)$/", TEMPLATES_DIR."/../actions/$1", $this->uri);
            $this->uri = preg_replace("/^\/ajax\/(\w+)$/", TEMPLATES_DIR."/../actions/ajax/$1", $this->uri);
        }

        foreach ($this->withoutTemplate as $noTemp) //checking na tamplate
        {
            //ci uri konci $notemp
            if (substr_compare($this->uri, $noTemp, -strlen($noTemp)) == 0)
            {
                $this->hasTemplate = false;
                break;
            }
        }
        foreach ($this->public as $pub) //checking na public
        {
            //ci uri konci $pub
            if (substr_compare($this->uri, $pub, -strlen($pub)) == 0)
            {
                $this->isPublic = true;
                break;
            }
        }

        $this->uri = realpath($this->uri.'.php');
        if (!file_exists($this->uri) || is_dir($this->uri))
            $this->fileExists = false;
    }

    public function getUri()
    {
        return $this->uri;
    }
}
?>
