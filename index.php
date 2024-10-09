<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title>Update database with new hashes</title>

<meta name="viewport" content="user-scalable=1, width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0" />

<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">

<link rel="stylesheet" type="text/css" href="styles.css" />

<link rel="stylesheet" type="text/css" href="form_styles.css" />

</head>

<body>

<div id='loginDiv'>

<?php
include('connect.php'); // $db = new MySQLi('localhost', 'user', 'password', ''); // db veld leeglaten

// maak database aan in MySQL of Mariadb

$query = 'CREATE DATABASE IF NOT EXISTS `demo_hashes`;';
$result = $db->query($query);
if ($result):
	echo '<h3>Database `demo_hashes` created</h3>';
endif;

$query = 'USE `demo_hashes`;';
$result = $db->query($query);
if ($result):
	echo '<h3>Database `demo_hashes` used</h3>';
endif;

// maak table demo_users

$query = 'DROP TABLE IF EXISTS `demo_users`;';
$result = $db->query($query);
if ($result):
	echo '<h3>Table `demo_users` dropped</h3>';
endif;

$query = 'CREATE TABLE `demo_users` (
  `demo_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `demo_username` varchar(50) NOT NULL,
  `demo_hash_md5` varchar(50) NOT NULL
);';
$result = $db->query($query);
if ($result):
	echo '<h3>Table `demo_users` created</h3>';
endif;

// voeg gebruikers toe met md5 wachtwoord hash

$query = 'INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`) VALUES
("Jan", md5("Jan")),
("Piet", md5("Piet")),
("Klaas", md5("Klaas"));';

$result = $db->query($query);
if ($result):
	echo '<h3>3 Users added</h3>';
endif;

// # toon de gebruikers

$query = 'SELECT * FROM `demo_users` LIMIT 50;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;
endif;

// voeg een nieuw wachtwoordhash veld toe.

$query = "ALTER TABLE `demo_users`
ADD `demo_hash_nieuw` varchar(100) COLLATE 'utf8mb3_general_ci' NOT NULL;";
$result = $db->query($query);
if ($result):
	echo '<h3>Nieuw veld toegevoegd</h3>';
endif;

$query = 'SELECT * FROM `demo_users` LIMIT 50;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;
endif;

// werk demo_hash_nieuw bij.

$query = 'UPDATE `demo_users` SET `demo_hash_nieuw` = "bijwerken";';
$result = $db->query($query);
if ($result):
	echo '<h3>Nieuw veld bijgewerkt</h3>';
endif;

$query = 'SELECT * FROM `demo_users` LIMIT 50;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;
endif;

/*
# database is nu klaar
# de volgende stappen nemen om een nieuw wachtwoord hash te creeren.

# NIEUWE GEBRUIKER.

# Een nieuwe gebruiker krijgt direct een nieuw wachtwoord hash
# Het demo_hash_md5 veld krijgt de waarde "vervallen".
# De nieuw hash maak je aan in de code van de applicatie.

# bijvoorbeeld in PHP met $password = password_hash('Gerrit', PASSWORD_BCRYPT)
# Kijk hier : https://bytes.egestas.net/private/crypt_pw.php
# waarbij de nieuwe hash als voorbeeld wordt:
# $2y$10$FmDz4r8V.PKc8qx8hLURUOokciAdc5QJFpaYS7aOnVh7S8tnAbTqq
*/

$username = 'Gerrit';
$password = 'Gerrit';
$hash     = password_hash($password, PASSWORD_BCRYPT);
echo '<p>Hash = ' . $hash . '</p>';

// vervolgens met deze query in PHP invoegen

$query = "INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`, `demo_hash_nieuw`)
VALUES ( '" . $username . "', 'vervallen', '" . $hash . "');";
$result = $db->query($query);
if ($result):
	echo '<h3>Gebruiker <b>' . $username . '</b> toegevoegd</h3>';
endif;

/* In MySQL

# INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`, `demo_hash_nieuw`) VALUES ('Gerrit', 'vervallen', '$2y$10$FmDz4r8V.PKc8qx8hLURUOokciAdc5QJFpaYS7aOnVh7S8tnAbTqq');
*/
// En voor de zekerheid tonen

$query = 'SELECT * FROM `demo_users` LIMIT 50;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;;
endif;
?>

	<h3>Gebruiker Jan logt in</h3>

<?php
$query = "SELECT * FROM `demo_users` WHERE `demo_username` = 'Jan'";
$result = $db->query($query);
$row = $result->fetch_object();
echo '<pre>' . print_r($row, TRUE) . '</pre>';
if ($row->demo_hash_nieuw <> "bijwerken"):

	echo '<p>test op nieuwe hash en ga door met inloggen</p>';

elseif ($row->demo_hash_nieuw == "bijwerken"):

	echo '<p>test op md5 hash en werk zonodig nieuwe hash bij</p>';

	$password = 'Jan';
	if ( $row->demo_hash_md5 == md5( $password ) ):
		echo '<p>Jan is correct ingelogd</p>';
		$newhash = password_hash($password, PASSWORD_BCRYPT);
		$query = "UPDATE `demo_users` SET `demo_hash_nieuw` = '" . $newhash . "', `demo_hash_md5` = 'vervallen' WHERE `demo_username` = 'Jan';";
		echo '<p><code>' . $query . '</code></p>';
		if ( $result = $db->query($query) ):
			echo '<h4>Nieuwe hash voor Jan</h4>';
		else:
			echo '<h4>Nieuwe hash voor Jan niet gelukt</h4>';
		endif;
	else:
		echo '<h4>Verkeerd wachtwoord Jan</h4>';
	endif;

endif;
?>

	<h4>Even Jan controleren...</h4>

<?php
$query = "SELECT * FROM `demo_users` WHERE `demo_username` = 'Jan'";
$result = $db->query($query);
$row = $result->fetch_object();
echo '<pre>' . print_r($row, TRUE) . '</pre>';
?>

	<h3>Voor deze demo ook de anderen bijwerken...</h3>

<?php
/*
# of als oude hash of nieuwe hash onjuist, maak melding en begin opnieuw.

# volgorde van testen naar eigen inzicht

# Als op den duur ALLE wachtwoordhashes zijn aangepast
# dan kan veld `demo_hash_md5 worden gewist.

# voor demo werken we `demo_hash_nieuw` bij

# maar laten eerst alles zien
SELECT * FROM `demo_users`;

# dan uitvoeren
*/

$newhash = '$2y$10$CBWFr5AmWsuyVBEoxSzfweg3Hmv6OEH00oU/d3Yu3qiQRx8v0wXhS';
$query = "UPDATE `demo_users` SET
`demo_hash_md5` = 'vervallen',
`demo_hash_nieuw` = '" . $newhash . "' WHERE `demo_username` = 'Piet';";
$result = $db->query($query);

$newhash = '$2y$10$aiMItOqb/pz5SfVp45fSZuXn2JPsX/TwQvr53KM9d0ir2/PQG234u';
$query = "UPDATE `demo_users` SET
`demo_hash_md5` = 'vervallen',
`demo_hash_nieuw` = '" . $newhash . "' WHERE `demo_username` = 'Klaas';";
$result = $db->query($query);

# laten alles zien
$query = 'SELECT * FROM `demo_users`;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;
endif;
?>

	<h3>Gebruiker Jan logt in met nieuwe hash</h3>

<?php
$query = "SELECT * FROM `demo_users` WHERE `demo_username` = 'Jan'";
$result = $db->query($query);
$row = $result->fetch_object();
echo '<pre>' . print_r($row, TRUE) . '</pre>';
if ($row->demo_hash_nieuw <> "bijwerken"):
	echo '<p>test op nieuwe hash en ga door met inloggen</p>';
	$password = 'Jan'; //komt van inlogform
	if ( password_verify( $password, $row->demo_hash_nieuw ) ):
		echo "<p>Password is valid</p>";
	else:
		echo "<p>Password is <b>not</b> valid</p>";
	endif;
endif;
?>

	<h3>Verwijderen veld `demo_hash_md5`</h3>

<?php
$query = 'ALTER TABLE `demo_users` DROP `demo_hash_md5`;';
$result = $db->query($query);

# laten weer alles zien
$query = 'SELECT * FROM `demo_users`;';
$result = $db->query($query);
if ($result):
	while( $row = $result->fetch_object() ):
		echo '<pre>' . print_r($row,TRUE) . '</pre>';
	endwhile;
endif;
?>

	<hr style="border: none; border-bottom: 3px double navy;">

	<h3>Done</h3>

	<p>Hierna hoef je alleen op de nieuwe hash te controleren</p>

</div>

</body>

</html>
