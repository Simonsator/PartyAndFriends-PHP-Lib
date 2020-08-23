<?php
/**
 * User: simonsator
 * Date: 10.02.17
 * Time: 14:40
 */

namespace PartyAndFriends\Lib\PAFPlayer;
require_once('PAFPlayerManager.php');

class PAFPlayer {
	private $uniqueID;
	private $name;
	private $id;

	function __construct($pUUID, $pName, $pID) {
		$this->uniqueID = $pUUID;
		$this->name = $pName;
		$this->id = $pID;
	}

	public function getName() {
		return $this->name;
	}

	public function getUniqueID() {
		return $this->uniqueID;
	}

	public function getID() {
		return $this->id;
	}

	public function getFriends() {
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "players WHERE player_id IN(SELECT friend1_id FROM " . PAFPlayerManager::getInstance()->getTablePrefix() . "friend_assignment WHERE friend2_id='" . $this->id . "') OR player_id IN(SELECT friend2_id FROM fr_friend_assignment WHERE friend1_id='" . $this->id . "')");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		return $friends;
	}
}