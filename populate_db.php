<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>DigiNinja's NoSQLi Lab - Reset The Lab</title>
		<meta name="description" content="Reset the database back to defaults">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Robin Wood <robin@digi.ninja>">
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/> 
	</head>
	<body>
		<div style="float:right"><img src="/digininja_avatar.png" alt="DigiNinja avatar" /></div>
		<h1>Reset The Lab</h1>
		<p><a href="/">&laquo; Home</a></p>
		<p>
			Use this page to reset the lab to its initial state.
		</p>
	<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		require "debug.php";
		// From http://php.net/manual/en/mongodb.tutorial.library.php

		define ("INJECTION_CTF_KEY", "Magrathea");

		$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

		// This uses a different collection - ctf - so that the key can't easily
		// be leaked through the XSS lab

		print "<p><strong>Beginning Reset</strong></p>";
		print "<h2>CTF Collection</h2>";
		print "<p>";

		try {
			print "Working on the sans.ctf collection<br />\n";
			print "Dropping the collection<br />\n";
			$command =  new \MongoDB\Driver\Command(["drop" => "ctf"]);
			$result = $manager->executeCommand('sans', $command);
			print "Collection dropped<br />\n";
		} catch (MongoDB\Driver\Exception\RuntimeException $e) {
			print "Collection wasn't there to drop<br />\n";
		}

		print "Adding script injection CTF key: " . INJECTION_CTF_KEY . "<br />\n";
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert([ 'type' => 'key', "value" => INJECTION_CTF_KEY ]);
		$result = $manager->executeBulkWrite('sans.ctf', $bulk);
		if ($result->getInsertedCount() == 1) {
			print "CTF key inserted<br />\n";
		} else {
			print "Something went wrong, key not inserted<br />\n";
		}
		print "</p>";

		print "<h2>Users Collection</h2>";
		print "<p>";
		print "Working on the sans.users collection<br />\n";

		try {
			print "Dropping the collection:<br />\n";
			$command =  new \MongoDB\Driver\Command(["drop" => "users"]);
			$result = $manager->executeCommand('sans', $command);
			print "Collection dropped<br />\n";
		} catch (MongoDB\Driver\Exception\RuntimeException $e) {
			print "Collection wasn't there to drop<br />\n";
		}

		print "Adding users<br />\n";

		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert([ "type" => 'user', 'username' => "joe", "fullname" => "Joe Blogs", "phone" => "123", "pwd" => "joepassw" ]);
		$bulk->insert([ "type" => 'user', 'username' => "robin",  "fullname" => "Robin Wood", "phone" => "234","pwd" => "passw" ]);
		$bulk->insert([ "type" => 'user', 'username' => "sid",  "fullname" => "Sid James", "phone" => "444","pwd" => "james" ]);
		$bulk->insert([ "type" => 'admin', 'username' => "penny",  "fullname" => "Penny Dog", "phone" => "987","pwd" => "dog" ]);
		$bulk->insert([ "type" => 'user', 'username' => "satan",  "fullname" => "Satan Smith", "phone" => "666","pwd" => "dev1l" ]);
		$result = $manager->executeBulkWrite('sans.users', $bulk);
		printf("Inserted %d userss\n", $result->getInsertedCount());

		print "</p>";
		
		print "<h2>Login Collection</h2>";
		print "<p>";
		print "Working on the sans.login collection<br />\n";

		try {
			print "Dropping the collection:<br />\n";
			$command =  new \MongoDB\Driver\Command(["drop" => "login"]);
			$result = $manager->executeCommand('sans', $command);
			print "Collection dropped<br />\n";
		} catch (MongoDB\Driver\Exception\RuntimeException $e) {
			print "Collection wasn't there to drop<br />\n";
		}

		print "Adding login<br />\n";

		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert([ "type" => 'user', 'username' => "joe", "fullname" => "Joe Blogs", "phone" => "123", "pwd" => "8uoijlsdaf" ]);
		$bulk->insert([ "type" => 'user', 'username' => "robin",  "fullname" => "Robin Wood", "phone" => "234","pwd" => "987yihuaserWRsdf" ]);
		$bulk->insert([ "type" => 'user', 'username' => "sid",  "fullname" => "Sid James", "phone" => "444","pwd" => "213qjwneasadfSdf" ]);
		$bulk->insert([ "type" => 'admin', 'username' => "penny",  "fullname" => "Penny Dog", "phone" => "987","pwd" => "jlkasdfijEasdf" ]);
		$bulk->insert([ "type" => 'user', 'username' => "satan",  "fullname" => "Satan Smith", "phone" => "666","pwd" => "56tuygjhFRAF" ]);
		$result = $manager->executeBulkWrite('sans.login', $bulk);
		printf("Inserted %d logins\n", $result->getInsertedCount());
		print "</p>";
		
		print "<p><strong>Database Reset Successfully</strong></p>";
	}
	?>
		<form method="post" action="populate_db.php">
			<input type="submit" name="submit" value="Reset" />
		</form>
	</body>
</html>
