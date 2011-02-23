<?php
/**
 * trieda reprezentujuca jeden zaznam z tabulky BANNERY
 *
 * @author yohny
 */
class Banner extends BanRek
{
    /**
     * nazov suboru banneru (stlpec sa vola 'path')
     * @var string
     */
    public $filename;

    public function __construct($id, $userId, Velkost $velkost, $filename)
    {
        parent::__construct($id, $userId, $velkost);
        $this->filename = $filename;
    }

    public function save($kategorie)
    {
        $db = Context::getInstance()->getDatabase();
        $db->conn->autocommit(false);
        $query = "INSERT INTO bannery VALUES(NULL, $this->userId, {$this->velkost->id}, '$this->filename')";
        if (!$db->conn->query($query))
        {
            $db->conn->rollback();
            $db->conn->autocommit(true);
            return false;
        }
        $this->id = $db->conn->insert_id;
        foreach ($kategorie as $kat)
        {
            $query = "INSERT INTO kategoria_banner VALUES (NULL, $kat, $this->id)";
            if (!$db->conn->query($query))
            {
                $db->conn->rollback();
                $db->conn->autocommit(true);
                return false;
            }
        }
        $db->conn->commit();
        $db->conn->autocommit(true);
        return true;
    }

    public function delete()
    {
        if (!unlink(Config::getUploadDir() . $this->filename))
            return false;
        return parent::delete();
    }

    public function __toString()
    {
        return htmlspecialchars(preg_replace('/^(\w+_\d+x\d+_)/', '', $this->filename));
    }

    /**
     * vrati image resourse s doplnenym watermarkom
     * @return image resource
     */
    public function getImgWithWatermark()
    {
        if (!$fileContent = @file_get_contents(Config::getUploadDir() . $this->filename))
            return null;
        $img = imagecreatefromstring($fileContent);
        $watermark = imagecreate(imagesx($img), 15);
        imagecolorallocate($watermark, 0, 0, 0); //black - first color becomes background
        $white = imagecolorallocate($watermark, 255, 255, 255);
        if(@imagettftext($watermark, 10, 0, imagesx($img) - 40, 12, $white, Config::getBaseDir().'/img/Ubuntu-B.ttf', 'Ad-IS'))
            imagecopymerge($img, $watermark, 0, imagesy($img) - imagesy($watermark), 0, 0, imagesx($watermark), imagesy($watermark), 50);
        return $img;
    }

    /**
     * zo stringu vytvori platne meno suboru banneru
     * @param string $string
     * @return string
     */
    public static function createFilename($string, Velkost $velkost)
    {
        $string = stripslashes($string);                    //odstrani lomitka
        $string = preg_replace('/[\s+\'+]/', '_', $string); //nahradi medzery a ine nepovolene symboly podtrznikmi
        $string = preg_replace('/_+/', '_', $string);       //nahradi viacero podtrznikov jednym
        $string = mb_strtolower($string, "UTF-8");
        $notallowed = array("ľ", "š", "č", "ť", "ž", "ý", "á", "í", "é", "ú", "ä", "ó", "ô", "ň", "ĺ", "ŕ", "ř");
        $allowed = array("l", "s", "c", "t", "z", "y", "a", "i", "e", "u", "a", "o", "o", "n", "l", "r", "r");
        $string = str_replace($notallowed, $allowed, $string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        return Context::getInstance()->getUser() . "_" . $velkost->sirka . "x" . $velkost->vyska . "_" . $string;
    }

    /**
     * overi ci subor splna kriteria banneru
     * @param array $file
     */
    public static function checkFile($userfile, Velkost $velkost)
    {
        $message = null;
        if ($userfile['size'] == 0)
            $message .= "Prázdny súbor!<br>";
        if ($userfile['size'] > 20000) //limit 20KB
            $message .= "Príliš veľký súbor! (max. 20KB)<br>";
        $maxNameLength = 50 - strlen(Context::getInstance()->getUser()) - 2 - strlen($velkost->sirka . "x" . $velkost->vyska);
        if (strlen($userfile['name']) > $maxNameLength)
            $message .= "Príliš dlhý názov súboru! (max. $maxNameLength znakov)<br>";
        //file['type'] vyhodnocuje len na zaklade pripony a nie na zaklade hlavicky suboru ako getimagesize
        $info = getimagesize($userfile['tmp_name']);
        if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) //1=gif,2=jpg,3=png
            $message .= "Nepodporovaný súbor! (iba .gif, .jpg, .png)<br>";
        if ($info[0] != $velkost->sirka || $info[1] != $velkost->vyska) //[0]-sirka,[1]-vyska
            $message .= "Nesprávne rozmery banneru! ($velkost->nazov je $velkost->sirka x $velkost->vyska)<br>";
        if (Context::getInstance()->getUser()->hasBannerOfSize($velkost))
            $message .= "Už máte banner typu $velkost->nazov!<br>";
        return $message;
    }
}
?>
