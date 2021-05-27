<?php
if (!isset($pafPhpLibGlobal))
	return;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Library for Party and Friends</title>
</head>
<body>
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