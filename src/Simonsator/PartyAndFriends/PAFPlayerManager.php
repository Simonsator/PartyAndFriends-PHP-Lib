<?php

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

	/**
	 * @param PDO $pPod The connection to the MySQL database
	 * @param string $tablePrefix The prefix of the tables as set in the Party and Friends config
	 */
	public function __construct(PDO $pPod, string $tablePrefix)
	{
		self::$instance = $this;
		$this->connection = $pPod;
		$this->tablePrefix = $tablePrefix;
	}

	/**
	 * @return PAFPlayerManager Returns the instance of the PAFPlayerManager. Make sure that it was initialized using the constructor before using this method
	 */
	public static function getInstance(): PAFPlayerManager
	{
		return self::$instance;
	}

	/**
	 * @param string $pUUID The uuid of the player
	 * @return PAFPlayer|null Returns the player if they ever joined the server or null if they did never join this server
	 */
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
	 * @return String Returns the prefix of the tables as set in the Party and Friends config
	 */
	public function getTablePrefix(): string
	{
		return $this->tablePrefix;
	}

	/**
	 * @param int $pID The id of the player as used in the party and friends database
	 * @return PAFPlayer|null Returns the corresponding player if the id belongs to a player or null if the id does not belong to a player
	 */
	public function getPlayerByID(int $pID): ?PAFPlayer
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

	/**
	 * @param string $pPlayerName The name of the player
	 * @return PAFPlayer|null Returns the player if they ever joined the server or null if they did never join this server
	 */
	public function getPlayerByName(string $pPlayerName): ?PAFPlayer
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