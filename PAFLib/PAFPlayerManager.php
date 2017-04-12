<?php
/**
 * User: Simonsator
 * Date: 10.02.17
 * Time: 14:28
 */

namespace PartyAndFriends\Lib\PAFPlayer;
require_once('PAFPlayer.php');

/**
 * Class PAFPlayerManager This class is to get
 * @package PartyAndFriends\Lib\PAFPlayer
 */
class PAFPlayerManager
{
    private $connection;
    private $tablePrefix;
    private static $instance;

    function __construct($pPod, $tablePrefix)
    {
        self::$instance = $this;
        $this->connection = $pPod;
        $this->tablePrefix = $tablePrefix;
    }

    public function getPlayerByUUID($pUUID)
    {
        $stmt = $this->connection->prepare("SELECT player_id, player_uuid, player_name FROM fr_players WHERE player_uuid=:uuid LIMIT 1");
        $stmt->bindParam(':uuid', $pUUID);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return NULL;
        }
        $row = $stmt->fetch();
        return new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
    }

    public function getPlayerByID($pID)
    {
        $stmt = $this->connection->prepare("SELECT player_id, player_uuid, player_name FROM fr_players WHERE player_id=:id LIMIT 1");
        $stmt->bindParam(':id', $pID);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return NULL;
        }
        $row = $stmt->fetch();
        return new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function getPlayerByName($pPlayerName)
    {
        $stmt = $this->connection->prepare("SELECT player_id, player_uuid, player_name FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "players WHERE player_name=:name LIMIT 1");
        $stmt->bindParam(':name', $pPlayerName);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            return NULL;
        }
        $row = $stmt->fetch();
        return new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
    }

    /**
     * @return String
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

}