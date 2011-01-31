<?php
/**
 * parent class for Banner and Reklama
 *
 * @author yohny
 */
class BanRek
{
    /**
     * primarny kluc
     * @var int
     */
    public $id;
    /**
     * FK na pouzivatela vlastniaceho banner/reklamu
     * @var int
     */
    public $userId;
    /**
     * objekt velkosti banneru/reklamy
     * @var Velkost
     */
    public $velkost;
    /**
     * pole kategorii banneru/reklamy
     * @var Kategoria
     */
    private  $kategorie = array();
    private $table = null;
    private $column = null;

    public function __construct($id, $userId, Velkost $velkost)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->velkost = $velkost;
        if(get_class($this)=='Banner')
        {
            $this->table = 'bannery';
            $this->column = 'banner';
        }
        elseif (get_class($this)=='Reklama')
        {
            $this->table = 'reklamy';
            $this->column = 'reklama';
        }
    }

    public function getKategorie()
    {
        if(!$this->table || !$this->column)
            return false;

        if (empty($this->kategorie))
        {
            $db = Context::getInstance()->getDatabase();
            $query = "SELECT kategorie.* FROM kategoria_$this->column JOIN kategorie ON (kategoria_$this->column.kategoria=kategorie.id)
            WHERE $this->column=$this->id ORDER BY kategorie.nazov ASC";
            /* @var $result mysqli_result */
            $results = $db->conn->query($query);
            while ($result = $results->fetch_object())
            {
                $object = new Kategoria($result->id, $result->nazov);
                $this->kategorie[] = $object;
            }
        }
        return $this->kategorie;
    }

    public function delete()
    {
        if(!$this->table || !$this->column)
            return false;

        $db = Context::getInstance()->getDatabase();
        $db->conn->autocommit(false);
        if (!$db->conn->query("DELETE FROM kategoria_$this->column WHERE $this->column=$this->id") ||
                !$db->conn->query("DELETE FROM $this->table WHERE id=$this->id"))
        {
            $db->conn->rollback();
            $db->conn->autocommit(true);
            return false;
        }
        $db->conn->commit();
        $db->conn->autocommit(true);
        return true;
    }
}
?>
