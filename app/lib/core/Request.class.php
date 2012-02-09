<?php
/**
 * trieda reprezentuje aktualnu poziadavku uzivatela (http request)
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage core
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Request
{
    /**
     * adresa pozadovana uzivatelom (request_uri) bez query stringu
     * null|false ak neexistujuci subor je pozadovany
     * @var string
     */
    private $uri = null;

    /**
     * pole nazvov ckriptov (bez .php), ktore su pristupne bez prihlasenia
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

        //maping logickych url adries na fyzicke cesty
        $this->uri = preg_replace("/^\/action\/(\w+)$/", ACTIONS_DIR."/$1", $this->uri);
        $this->uri = preg_replace("/^\/ajax\/(\w+)$/", ACTIONS_DIR."/ajax/$1", $this->uri);
        $this->uri = preg_replace("/^\/(\w+)$/", TEMPLATES_DIR."/content/$1", $this->uri);
        $this->uri = preg_replace("/^\/$/", TEMPLATES_DIR."/content/about", $this->uri);

        if (preg_match("/^".preg_quote(ACTIONS_DIR,"/")."/", $this->uri))//ak to je 'akcia'
        {
            $this->hasTemplate = false;
            Context::getInstance()->getResponse()->setHeaderContentType("text/plain");//defaultny typ pre akcie
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
    }

    public function getUri()
    {
        return $this->uri;
    }
}
?>
