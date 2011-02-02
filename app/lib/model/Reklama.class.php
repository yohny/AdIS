<?php
/**
 * trieda reprezentujuca jeden zaznam z tabulky REKLAMY
 *
 * @author yohny
 */
class Reklama extends BanRek
{
    /**
     * meno reklamy (stlpec v tabulke sa vola 'meno')
     * @var string
     */
    public $name;

    public function __construct($id, $userId, Velkost $velkost, $name)
    {
        parent::__construct($id, $userId, $velkost);
        $this->name = $name;
    }

    public function save($kategorie)
    {
        $db = Context::getInstance()->getDatabase();
        $db->conn->autocommit(false);
        $query = "INSERT INTO reklamy VALUES(NULL, $this->userId, {$this->velkost->id}, '$this->name')";
        if (!$db->conn->query($query))
        {
            $db->conn->rollback();
            $db->conn->autocommit(true);
            return false;
        }
        $this->id = $db->conn->insert_id;
        foreach ($kategorie as $kat)
        {
            $query = "INSERT INTO kategoria_reklama VALUES (NULL, $kat, $this->id)";
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
        return parent::delete();
    }

    public function __toString()
    {
        return htmlspecialchars($this->name);
    }

    public static function checkAd($meno, Velkost $velkost)
    {
        $message = null;
        if (strlen($meno) > 50)
            $message .= "Príliš dlhý názov! (max. 50 znakov)<br>";
        if (Context::getInstance()->getUser()->hasReklamaOfSize($velkost))
            $message .= "Už máte reklamu typu $velkost->nazov!<br>";
        return $message;
    }
}
?>
