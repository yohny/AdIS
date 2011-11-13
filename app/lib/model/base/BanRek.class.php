<?php
/**
 * spoloscny predok pre Banner a Reklama
 * 
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
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
            $results = $db->query($query);
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
        $db->autocommit(false);
        if (!$db->query("DELETE FROM kategoria_$this->column WHERE $this->column=$this->id") ||
                !$db->query("DELETE FROM $this->table WHERE id=$this->id"))
        {
            $db->rollback();
            $db->autocommit(true);
            return false;
        }
        $db->commit();
        $db->autocommit(true);
        return true;
    }
}
?>
