<html>
<head>

</head>
<body>
<?php
/**
 * User: Simonsator
 * Date: 10.02.17
 * Time: 14:54
 */
if (empty($_GET['name']) && empty($_GET['uuid']) && empty($_GET['id'])) { ?>
<form method='get'>
    <label>
        Find player by player name:
        <input type='text' max='16' placeholder='Player name' name='name'>
    </label>
    <input type='submit' name='submit'>
</form>
<form method='get'>
    <label>
        Find player by UUID:
        <input type='text' minlength='36' maxlength='36' name='uuid'
               placeholder='Player UUID'>
    </label>
    <input type='submit' name='submit'>
</form>
<form method='get'>
    <label>
        Find player by ID:
        <input type='number' name='id' placeholder='Player id'>
    </label>
    <input type='submit' name='submit'>
</form>
</body>
</html>
<?php
return;
}
require_once('../PAFPlayerManager.php');

use PartyAndFriends\Lib\PAFPlayer\PAFPlayerManager;

require_once('../config.php');
$pod = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $password);
$manager = new PAFPlayerManager($pod, $tablePrefix);
if (!empty($_GET['name'])) {
	$givenPlayer = $manager->getPlayerByName($_GET['name']);
} elseif (!empty($_GET['uuid'])) {
	$givenPlayer = $manager->getPlayerByUUID($_GET['uuid']);
} elseif (!empty($_GET['id'])) {
	$givenPlayer = $manager->getPlayerByID($_GET['id']);
} else {
	$givenPlayer = NULL;
}
?>
<html lang="en">
<head>

    <title>Example PAFLib</title></head>
<body>
<?php
if (is_null($givenPlayer)) {
	echo "The given player does not exist</body></html>";
	return;
}
echo "The UUID of the given player is: " . $givenPlayer->getUniqueID();
echo "<br>The name of the given player is: " . $givenPlayer->getName();
echo "<br>The id of the given player is: " . $givenPlayer->getID();
$friends = $givenPlayer->getFriends();
echo "</br>Friends: ";
if (is_array($friends)) {
	foreach ($friends as $friend) {
		echo "<br> - " . $friend->getName();
	}
} else {
	echo "The player does not have added friends yet.";
}
$friendRequests = $givenPlayer->getFriendRequests();
echo "</br>Received friend requests: ";
if (is_array($friendRequests)) {
	foreach ($friendRequests as $friendRequest) {
		echo "<br> - " . $friendRequest->getName();
	}
} else {
	echo "The player did not receive any friends requests.";
}
$friendRequests = $givenPlayer->getSentFriendRequests();
echo "</br>Sent friend requests: ";
if (is_array($friendRequests)) {
	foreach ($friendRequests as $friendRequest) {
		echo "<br> - " . $friendRequest->getName();
	}
} else {
	echo "The player did not receive any friends requests.";
}
?>
</body>
</html>
