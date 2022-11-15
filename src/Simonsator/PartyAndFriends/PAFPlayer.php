<?php

namespace Simonsator\PartyAndFriends;

class PAFPlayer
{
	private string $uniqueID;
	private string $name;
	private int $id;

	function __construct(string $pUUID, string $pName, int $pID)
	{
		$this->uniqueID = $pUUID;
		$this->name = $pName;
		$this->id = $pID;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getUniqueID(): string
	{
		return $this->uniqueID;
	}

	public function getFriends(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "players WHERE player_id IN(SELECT friend1_id FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_assignment WHERE friend2_id='" . $this->id . "') OR player_id IN(SELECT friend2_id FROM fr_friend_assignment WHERE friend1_id='" . $this->id . "')");
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

	public function getFriendRequests(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "players WHERE player_id IN(SELECT requester_id FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_request_assignment WHERE receiver_id='" . $this->id . "')");
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

	public function getSentFriendRequests(): array
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "players WHERE player_id IN(SELECT receiver_id FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_request_assignment WHERE requester_id='" . $this->id . "')");
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

	public function getSettingsWorth(int $pSettingsID): int
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT settings_worth FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "settings WHERE player_id = '" . $this->id . "' AND settings_id = '" . $pSettingsID . "' LIMIT 1");
		$stmt->execute();
		if (isset($stmt[0]))
			return $stmt[0]['settings_worth'];
		return 0;
	}

	public function addFriend(PAFPlayer $player)
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("INSERT INTO " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_assignment VALUES ('" . $this->id . "', '" . $player->getID() . "')");
		$stmt->execute();
	}

	public function getID(): int
	{
		return $this->id;
	}

	public function sendFriendRequest(PAFPlayer $player)
	{
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("INSERT INTO " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_assignment VALUES ('" . $this->id . "', '" . $player->getID() . "')");
		$stmt->execute();
	}

}