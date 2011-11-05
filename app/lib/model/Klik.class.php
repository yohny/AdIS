<?php
/**
 * trieda reprezentujuca zaznam z tabulky KLIKY
 * 
 * @version    1.0
 * @package    AdIS
 * @subpackage model
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */
class Klik extends Event
{
    public function save(Database $db)
    {
        $query = "INSERT INTO kliky VALUES(NULL, NOW(), $this->zobraId, $this->inzerId, $this->reklamaId, $this->bannerId)";
        return $db->conn->query($query);
    }
}
?>
