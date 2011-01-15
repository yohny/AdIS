<?php
/**
 * trieda reprezentujuca jeden zaznam z tabulky BANNERY
 *
 * @author yohny
 */
class Banner
{
    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * FK na pouzivatela vlastniaceho banner
     * @var int
     */
    public $userId;
    /**
     * objekt velkosti banneru
     * @var Velkost
     */
    public $velkost;
    /**
     * nazov suboru banneru bez cesty (stlpec v tabulke sa vola 'path')
     * @var string
     */
    public $filename; //path in table
    public function __construct($id, $userId, Velkost $velkost, $filename)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->velkost = $velkost;
        $this->filename = $filename;
    }

    public function getKategorie()
    {
        $db = Context::getInstance()->getDatabase();
        $query = "SELECT kategorie.* FROM kategoria_banner JOIN kategorie ON (kategoria_banner.kategoria=kategorie.id)
            WHERE banner=$this->id ORDER BY kategorie.nazov ASC";
        /* @var $result mysqli_result */
        $results = $db->conn->query($query);
        $objects = array();
        while ($result = $results->fetch_object())
        {
            $object = new Kategoria($result->id, $result->nazov);
            $objects[] = $object;
        }
        return $objects;
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
        $db = Context::getInstance()->getDatabase();
        if (!unlink('upload/' . $this->filename)) //relat cesta voci zmaz.php, kde je tato metoda volana
            return false;
        $db->conn->autocommit(false);
        if (!$db->conn->query("DELETE FROM kategoria_banner WHERE banner=$this->id") ||
                !$db->conn->query("DELETE FROM bannery WHERE id=$this->id"))
        {
            $db->conn->rollback();
            $db->conn->autocommit(true);
            return false;
        }
        $db->conn->commit();
        $db->conn->autocommit(true);
        return true;
    }

    public function __toString()
    {
        return preg_replace('/^(\w+_\d+x\d+_)/', '', $this->filename);
    }

    /**
     * zo stringu vytvori platne meno suboru banneru
     * @param string $string
     * @return string
     */
    public static function createFilename($string, Velkost $velkost)
    {
        $string = stripslashes($string);                        //odstrani lomitka
        $string = preg_replace('/[\s+\'+]/', '_', $string);     //nahradi medzery a ine nepovolene symboly podtrznikmi
        $string = preg_replace('/_+/', '_', $string);           //nahradi viacero podtrznikov jednym
        $string = mb_strtolower($string, "UTF-8");
        $notallowed = array("ľ", "š", "č", "ť", "ž", "ý", "á", "í", "é", "ú", "ä", "ó", "ô", "ň", "ĺ", "ŕ", "ř");  //nahradi nepovolene znaky
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
