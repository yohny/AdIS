<?php
/**
 * reprezentuje uzivatelsky request
 *
 * @author yohny
 */
class Request
{
    private $uri = '';
    /**
     * pole nazvov suborov alebo ciest (bez .php), ktore su bez layoutu
     * !po mapingu na fyzicke cesty
     * @var regexp
     */
    private $withoutTemplate = array(
        'checklogin',
        'chpas',
        'chweb',
        'prihlas',
        'odhlas',
        'registruj',
        'zmaz',
        'pridajBanner',
        'pridajReklamu');
    /**
     * pole nazvov suborov alebo ciest (bez .php), ktore su pristupne bez lognutia
     * !po mapingu na fyzicke cesty
     * @var string
     */
    private $public = array(
        'about',
        'faq',
        'registracia',
        'checklogin',
        'klikerror',
        'prihlas',
        'registruj',);
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

    public function __construct($redir_url)
    {
        //odstrani lomitko na zaciatku a pripadne .php na konci
        // (lebo apache moze doplnat .php ak tym vznikne existujuci subor)
        $this->uri = preg_replace(array("/^\//", "/\.php$/"), "", $redir_url);
        if ($this->uri == '')//index
            $this->uri = 'templates/content/about';
        else
        {
            //maping logickych url adries na fyzicke cesty
            $this->uri = preg_replace("/^(\w+)$/", "templates/content/$1", $this->uri);
            $this->uri = preg_replace("/^action\/(\w+)$/", "actions/$1", $this->uri);
            $this->uri = preg_replace("/^ajax\/(\w+)$/", "actions/ajax/$1", $this->uri);
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

        $this->uri.='.php';
        if (!file_exists($this->uri) || is_dir($this->uri))
            $this->fileExists = false;
    }

    public function getUri()
    {
        return $this->uri;
    }
}
?>
