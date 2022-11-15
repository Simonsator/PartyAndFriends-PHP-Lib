<?php

namespace Simonsator\PartyAndFriends;

class PAFPlayer
{
	private string $uniqueID;
	private string $name;
	private int $id;

	/**
	 * @param string $pUUID
	 * @param string $pName
	 * @param int $pID
	 *
	 * This constructor should only be called from within the library. It should never be created by an external class.
	 */
	public function __construct(string $pUUID, string $pName, int $pID)
	{
		$this->uniqueID = $pUUID;
		$this->name = $pName;
		$this->id = $pID;
	}

	/**
	 * @return string The name of the player (not the display name)
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string The unique id of the player
	 */
	public function getUniqueID(): string
	{
		return $this->uniqueID;
	}

	/**
	 * @return PAFPlayer[] Returns an array with all friends of the player
	 */
	public function getFriends(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_id IN(
                       SELECT friend1_id 
                       FROM {$this->getTablePrefix()}friend_assignment 
                       WHERE friend2_id='{$this->id}'
                   ) OR player_id IN(
                       SELECT friend2_id 
                       FROM {$this->getTablePrefix()}friend_assignment 
                       WHERE friend1_id='{$this->id}'
                   )");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if (isset($friends))
			return $friends;
		return [];
	}

	private function getTablePrefix(): string
	{
		return PAFPlayerManager::getInstance()->getTablePrefix();
	}

	/**
	 * @return PAFPlayer[] Returns an array with containing all players that have sent a friend request to the player, which have not yet been denied or accepted
	 */
	public function getFriendRequests(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_id IN(
                       SELECT requester_id 
                       FROM {$this->getTablePrefix()}friend_request_assignment 
                       WHERE receiver_id='{$this->id}'
                   )");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if (isset($friends))
			return $friends;
		return [];
	}

	/**
	 * @return PAFPlayer[] Returns an array with all open friend requests this player sent to other players
	 */
	public function getSentFriendRequests(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"SELECT player_id, player_uuid, player_name 
                   FROM {$this->getTablePrefix()}players 
                   WHERE player_id IN(
                       SELECT receiver_id 
                       FROM {$this->getTablePrefix()}friend_request_assignment 
                       WHERE requester_id='{$this->id}'
                   )");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if (isset($friends))
			return $friends;
		return [];
	}

	/**
	 * @param int $pSettingsID The id of the setting. Extensions might add more settings, but by default, the plugin has the following settings:
	 *                         0 = Friend Request Setting
	 *                         1 = Party Invite Setting
	 *                         2 = PM Setting
	 *                         3 = Offline Setting
	 *                         4 = Jump Setting
	 *                         6 = Hide Setting
	 *
	 * @return int Returns the value of the setting
	 */
	public function getSettingsWorth(int $pSettingsID): int
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"SELECT settings_worth 
                   FROM {${$this->getTablePrefix()}}settings 
                   WHERE player_id = '{$this->id}' AND settings_id = '" . $pSettingsID . "' LIMIT 1");
		$stmt->execute();
		if (isset($stmt[0]))
			return $stmt[0]['settings_worth'];
		return 0;
	}

	/**
	 * @param PAFPlayer $player The player which should be the new friend of this player
	 * @return void
	 */
	public function addFriend(PAFPlayer $player)
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"INSERT INTO {$this->getTablePrefix()}friend_assignment 
                   VALUES ('{$this->id}', '{$player->getID()}')");
		$stmt->execute();
	}

	/**
	 * @return int Returns the id of the player as used in the party and friends database
	 */
	public function getID(): int
	{
		return $this->id;
	}

	/**
	 * @param PAFPlayer $player The player which should receive a friend request from this player
	 * @return void
	 */
	public function sendFriendRequest(PAFPlayer $player): void
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare(
			"INSERT INTO {$this->getTablePrefix()}friend_assignment 
                   VALUES ('{$this->id}', '{$player->getID()}')");
		$stmt->execute();
	}
}