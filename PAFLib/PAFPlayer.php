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
	private $settingMap;

	function __construct($pUUID, $pName, $pID) {
		$this->uniqueID = $pUUID;
		$this->name = $pName;
		$this->id = $pID;

		$this->settingMap = array(
			"hide" => 6,
			"notifications" => 101,
			"online" => 3,
			"receive" => 2,
			"invite" => 1,
			"requests" => 0
		);
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
		$prefix = PAFPlayerManager::getInstance()->getTablePrefix();
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM {$prefix}players WHERE player_id IN(SELECT friend1_id FROM {$prefix}friend_assignment WHERE friend2_id='{$this->id}') OR player_id IN(SELECT friend2_id FROM fr_friend_assignment WHERE friend1_id='{$this->id}')");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if(isset($friends))
			return $friends;
	}
	
	public function getFriendRequests() {
		$prefix = PAFPlayerManager::getInstance()->getTablePrefix();
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM {$prefix}players WHERE player_id IN(SELECT requester_id FROM {$prefix}friend_request_assignment WHERE receiver_id='{$this->id}')");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if(isset($friends))
			return $friends;
	}
	
	public function getSentFriendRequests() {
		$prefix = PAFPlayerManager::getInstance()->getTablePrefix();
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT player_id, player_uuid, player_name FROM {$prefix}players WHERE player_id IN(SELECT receiver_id FROM {$prefix}friend_request_assignment WHERE requester_id='{$this->id}')");
		$stmt->execute();
		$i = 0;
		foreach ($stmt as $row) {
			$friends[$i] = new PAFPlayer($row['player_uuid'], $row['player_name'], $row['player_id']);
			$i++;
		}
		if(isset($friends))
			return $friends;
	}

	public function getSetting($setting) {
		//try find setting in map
		if (in_array($setting, $this->settingMap)) {
			$settingId = $this->settingMap[$setting];
		} else {
			return;
		}

		//get setting
		$prefix = PAFPlayerManager::getInstance()->getTablePrefix();
		$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("SELECT * FROM {$prefix}settings WHERE player_id = {$this->id} AND settings_id = {$settingId}");
		$stmt->execute();

		foreach ($stmt as $row) {
			$worth = $row['settings_worth'];
		}

		//if the setting row exists, if not just return 0
		if (isset($worth)) {
			return $worth;
		} else {
			return 0;
		}
	}

	public function setSetting($setting, $worth) {
		if (in_array($setting, $this->settingMap)) {
			$settingId = $this->settingMap[$setting];
		} else {
			return false;
		}

		$prefix = PAFPlayerManager::getInstance()->getTablePrefix();
		if ($this->getSetting($setting) !== 0) {
			//already in db
			if ($worth == 0) {
				$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("DELETE FROM {$prefix}settings WHERE player_id = {$this->id} AND settings_id = {$settingId}");
				$stmt->execute();
				return true;
			} else {
				$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("UPDATE {$prefix}settings SET settings_worth = {$worth} WHERE player_id = {$this->id} AND settings_id = {$settingId}");
				$stmt->execute();
				return true;
			}
		} else {
			//not in db
			$stmt = PAFPlayerManager::getInstance()->getConnection()->prepare("INSERT INTO {$prefix}settings (player_id, settings_id, settings_worth) VALUES ({$this->id}, {$settingId}, {$worth})");
			$stmt->execute();
			return true;
		}
	}
}
