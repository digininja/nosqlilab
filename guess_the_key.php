<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>DigiNinja's NoSQLi Lab - Guess The Key</title>
		<meta name="description" content="Guess the key or exploit the system">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Robin Wood <robin@digi.ninja>">
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/> 
		<link rel='stylesheet' href='/css/style.css'>
		<script src="/lab.js" type="text/javascript"></script>
	</head>
	<body>
		<div style="float:right"><img src="/digininja_avatar.png" alt="DigiNinja avatar" /></div>
		<h1>Guess The Key</h1>
		<p><a href="/">&laquo; Home</a></p>
		<p>
			Play the game, see if you can guess the key.
		</p>
		<?php
		if (array_key_exists ("guess", $_GET)) {
			$guess = $_GET['guess']; 

			$script = "
			try {
				var conn = new Mongo();
				var db = conn.getDB('sans')
				var cursor = db.ctf.findOne({'type':'key'},{value:1, _id:0})
				var key=cursor.value;

				var guess='" . $guess . "';
				if (key == guess) {
					return('match');
				} else {
					return('No match');
				}
			} catch (err) {
				return err.message;
			}";

			$cmd = new \MongoDB\Driver\Command( [
				'eval' => $script
			] );

			try {
				$mongo_driver = new MongoDB\Driver\Manager("mongodb://localhost:27017");
				$cursor = $mongo_driver->executeCommand( 'sans', $cmd );
				$response = $cursor->toArray()[0];

				if ($response->retval) {
					print "<p>The server says: '" . $response->retval . "'</p>";
				}
			} catch (MongoDB\Driver\Exception\RuntimeException $e) {
				print "<p>";
				print "There was an error checking the guess:\n<br />";
				print "</p>";

				print "<p>";
				print "Exception: ". $e->getMessage(). "<br />\n";
				print "In file: ". $e->getFile(). "<br />\n";
				print "On line: ". $e->getLine(). "<br />\n";       

				print "Stack trace:\n<br />";
				print "<pre>" . $script . "</pre>";
				print "</p>";
			} catch (Exception $e) {
				var_dump($e);
				print "<p>There was an error trying the guess: '" . $e->getMessage() . "'</p>\n";

			}
		}
		?>
		<form method="GET">
			<label for="guess">Your guess: </label><input type="text" id="guess" name="guess" value="" />
			<input type="submit" value="Guess" />
		</form>

		<h2>Solutions</h2>
		<p id="show"><a href="#" onclick="show_notes()">Show</a></p>
		<p id="notes" class="hidden">
			This is a script injection lab, use a single quote to break out of the script and then build up a working statement based on the error message in the stack trace.<br />
			<a href="/guess_the_key.php?guess=%27+%3B+return+key%3B+%2F%2F+">Solution</a><br />
			This balances the quotes, adds the "return key" to get the key out and then comments out the rest of the line to stop the rogue single quote being a problem.<br />
			<a href="/guess_the_key.php?guess=%27+%3B+return+key%3B+var+a+%3D+%27a">Solution 2</a><br />
			Very similar but uses a new variable assignment to use up the rogue single quote. Might be useful if you wanted something else that is on that line to run.<br />
		</p>
	</body>
</html>
