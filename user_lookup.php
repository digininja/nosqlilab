<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>NoSQL Attack Lab - User Lookup</title>
	<meta name="description" content="Robin's NoSQL attack lab" />
	<link rel='stylesheet' href='/css/style.css'>
	<script src="/lab.js" type="text/javascript"></script>
</head>
<body>
	<div style="float:right"><img src="/digininja_avatar.png" alt="DigiNinja avatar" /></div>
	<h1>User Lookup Challenge</h1>
	<p><a href="/">&laquo; Home</a></p>
	<p>
		Get the details of an administrator. To get you started, sid is a user of the system<br />
		Bonus - Get the admin's password.
	</p>
	<p>
		Please enter a username below to look up their details.
	</p>
<?php
if (array_key_exists ("username", $_GET)) {
	require ("debug.php");
	// From http://php.net/manual/en/mongodb.tutorial.library.php
	// Security
	// http://php.net/manual/en/mongodb.security.request_injection.php
	// q[$ne]=foo

	$username = $_GET['username'];
	$type = $_GET['type'];

	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

	$filter = [ "type" => $type, "username" => $username ] ;
	$options = [];
	$query = new MongoDB\Driver\Query($filter, $options);
	$rows = $manager->executeQuery('sans.users', $query);

	$row_count = 0;
	foreach($rows as $user){
		if (array_key_exists ("fullname", $user)) {
			?>
			<p>
				<?php echo $user->_id, ': ' . $user->type . " - " . $user->fullname . " (" . $user->phone . ")<br />\n"; ?>
			</p>
			<?php
		}

		print "<!--" ; var_dump_pre ($user); print "-->";
		$row_count++;
	}

	if ($row_count == 0) {
		?>
		<p>User not found</p>
		<?php
	}
}
?>
<form method="GET">
	<input type="hidden" name="type" value="user" />
	<label for="username">Enter a username: </label><input type="text" id="username" name="username" value="" />
	<input type="submit" value="Guess" />
</form>

	<h2>Solutions</h2>
	<p id="show"><a href="#" onclick="show_notes()">Show</a></p>
	<p id="notes" class="hidden">
		If we want this to be a little easier and only need one field attacking then make the username
		field a regex by adding [$regex] to the find and then have them attack the "hidden" type
		value instead.<br />
		<a href="/user_lookup.php?type[$ne]=user&username[$ne]=robin">Not Equals Solution</a><br />
		<a href="/user_lookup.php?type[$regex]=.*&username[$regex]=.*">Regex Solution</a>
	</p>
</body>
</html>
