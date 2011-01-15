<?php
/**
 * trieda reprezentujuca zaznam z tabulky KLIKY
 *
 * @author yohny
 */
class Zobrazenie extends Event
{

    public function save(Database $db)
    {
        $query = "INSERT INTO zobrazenia VALUES(NULL, NOW(), $this->zobraId, $this->inzerId, $this->reklamaId, $this->bannerId)";
        return $db->conn->query($query);
    }
}
?>
