<?php
/**
 * @var PAFPlayer $givenPlayer
 */

use PartyAndFriends\Lib\PAFPlayer\PAFPlayer;

if (!isset($pafPhpLibGlobal))
	return;

function listPlayers(array $players, string $emptyMessage) {
	if (is_array($players) && sizeof($players) > 0) {
		?>
        <ul>
			<?php
			foreach ($players as $player) {
				?>
                <li><?php echo $player->getName(); ?></li>
				<?php
			}
			?>
        </ul>
		<?php
	} else {
		echo $emptyMessage;
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Library for Party and Friends</title>
</head>
<body>
<p>The UUID of the given player is: <?php echo $givenPlayer->getUniqueID(); ?></p>
<p>The name of the given player is: <?php echo $givenPlayer->getName(); ?></p>
<p> The id of the given player is: <?php echo $givenPlayer->getID(); ?></p>
<p>Friends:</p>
<?php
listPlayers($givenPlayer->getFriends(), "The player does not have added friends yet.");
?>
<p>Received friend requests:</p>
<?php
listPlayers($givenPlayer->getFriendRequests(), "The player did not receive any friends requests.");
?>
<p>Sent friend requests:</p>
<?php
listPlayers($givenPlayer->getSentFriendRequests(), "The player did not send any friends requests.");
?>
</body>
</html>