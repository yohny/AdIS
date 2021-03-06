<?php
/**
 * trieda reprezentujuca zaznam z tabulky ZOBRAZENIA
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Zobrazenie extends Event
{
    private $clicked;

    public function __construct($id, DateTime $cas, $zobraId, $reklamaId, $inzerId, $bannerId, $clicked = false)
    {
        parent::__construct($id, $cas, $zobraId, $reklamaId, $inzerId, $bannerId);
        $this->clicked = $clicked;
    }


    public function save(Database $db)
    {
        $query = "INSERT INTO zobrazenia VALUES(NULL, NOW(), $this->zobraId, $this->inzerId, $this->reklamaId, $this->bannerId, 0)";
        return $db->query($query);
    }

    /**
     * overi ci zobrazenie uz bolo kliknute
     * @return bool
     */
    public function isClicked()
    {
        return $this->clicked;
    }

    /**
     * nastavi zobrazenie na kliknute
     * @return bool
     */
    public function setClicked(Database $db)
    {
        $query = "UPDATE zobrazenia SET clicked=1 WHERE id=$this->id";
        if($ret = $db->query($query))
            $this->clicked = true;
        return $ret;
    }

    /**
     * zmaze zobrazenie
     * @return bool
     */
    public function delete(Database $db)
    {
        $query = "DELETE FROM zobrazenia WHERE id=$this->id";
        return $db->query($query);
    }
}
?>
