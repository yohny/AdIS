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
     * pole regexpov, ktore ked matchnu tak nie je potrebny layout
     * !po mapingu na fyzicke subory
     * @var regexp
     */
    private $withoutTemplate = array('/^distrib\//','/^actions\//');
    /**
     * pole nazvov suborov (bez .php) ktore su pristupne bez lognutia
     * @var string
     */
    private $public = array('about','faq','registracia','checklogin','klikerror','prihlas','registruj');
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
     * alebo len zaregistrovanym userom
     * @var bool
     */
    public $isPublic = false;

    public function __construct($redir_url)
    {
        //odstrani lomitko na zaciatku a pripadne .php na konci (lebo apache doplna .php ak tym vznikne existujuci subor)
        $this->uri = preg_replace(array("/^\//","/\.php$/"), "", $redir_url);
        if($this->uri=='')//index
            $this->uri = 'templates/content/about.php';
        else
        {
            //maping logickych url adries na fyzicke subory
            $this->uri = preg_replace("/^(\w+)$/", "templates/content/$1.php", $this->uri);
            $this->uri = preg_replace("/^action\/(\w+)$/", "actions/$1.php", $this->uri);
            $this->uri = preg_replace("/^ajax\/(\w+)$/", "actions/ajax/$1.php", $this->uri);
        }

        foreach ($this->withoutTemplate as $noTemplate)
        {
            if(preg_match($noTemplate, $this->uri))
            {
                $this->hasTemplate = false;
                break;
            }
        }
        foreach ($this->public as $pub)
        {
            if(preg_match('/'.$pub.'\.php$/', $this->uri))
            {
                $this->isPublic = true;
                break;
            }
        }
        if(!file_exists($this->uri) || is_dir($this->uri))
            $this->fileExists = false;

        //var_dump($this);
    }

    public function getUri()
    {
        return $this->uri;
    }

}
?>
