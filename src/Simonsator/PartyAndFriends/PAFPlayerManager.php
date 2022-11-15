<?php
/**
 * User: Simonsator
 * Date: 10.02.17
 * Time: 14:28
 */

namespace Simonsator\PartyAndFriends;

use PDO;

/**
 * Class PAFPlayerManager This class is used to get
 * @package PartyAndFriends\Lib\PAFPlayer
 */
class PAFPlayerManager
{
	private static PAFPlayerManager $instance;
	private PDO $connection;
	private string $tablePrefix;

	public function __construct(PDO $pPod, string $tablePrefix)
	{
		self::$instance = $this;
		$this->connection = $pPod;
		$this->tablePrefix = $tablePrefix;
	}

	public static function getInstance(): PAFPlayerManager
	{
		return self::$instance;
	}

	public function getPlayerByUUID(string $pUUID): ?PAFPlayer
	{
		$stmt = $this->connection->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_uuid=:uuid LIMIT 1");
		$stmt->bindParam(':uuid', $pUUID);
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
	public function getTablePrefix(): string
	{
		return $this->tablePrefix;
	}

	public function getPlayerByID($pID): ?PAFPlayer
	{
		$stmt = $this->connection->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_id=:id LIMIT 1");
		$stmt->bindParam(':id', $pID);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			return NULL;
		}
		$row = $stmt->fetch();
		return new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
	}

	public function getConnection(): PDO
	{
		return $this->connection;
	}

	public function getPlayerByName($pPlayerName): ?PAFPlayer
	{
		$stmt = $this->connection->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_name=:name LIMIT 1");
		$stmt->bindParam(':name', $pPlayerName);
		$stmt->execute();
		if ($stmt->rowCount() == 0) {
			return NULL;
		}
		$row = $stmt->fetch();
		return new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
	}
}