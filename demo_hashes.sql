# maak database aan in MySQL of Mariadb

CREATE DATABASE IF NOT EXISTS `demo_hashes`;

USE `demo_hashes`;

# maak table demo_users
DROP TABLE IF EXISTS `demo_users`;
CREATE TABLE `demo_users` (
  `demo_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `demo_username` varchar(50) NOT NULL,
  `demo_hash_md5` varchar(50) NOT NULL
);

# voeg gebruikers toe met md5 wachtwoord hash

INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`)
VALUES ('Jan', md5('Jan'));

INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`)
VALUES ('Piet', md5('Piet'));

INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`)
VALUES ('Klaas', md5('Klaas'));

# toon de gebruikers

SELECT * FROM `demo_users` LIMIT 50;

# voeg een nieuw wachtwoordhash veld toe.

ALTER TABLE `demo_users`
ADD `demo_hash_nieuw` varchar(100) COLLATE 'utf8mb3_general_ci' NOT NULL;

# werk demo_hash_nieuw bij.

UPDATE `demo_users` SET `demo_hash_nieuw` = "bijwerken";
SELECT * FROM `demo_users` LIMIT 50;

# database is nu klaar
# de volgende stappen nemen om een nieuw wachtwoord hash te creeren.

# NIEUWE GEBRUIKER.

# Een nieuwe gebruiker krijgt direct een nieuw wachtwoord hash
# Het demo_hash_md5 veld krijgt de waarde "vervallen".
# De nieuw hash maak je aan in de code van de applicatie.

# bijvoorbeeld in PHP met $password = password_hash('Gerrit', PASSWORD_BCRYPT)
# Kijk hier : https://bytes.egestas.net/private/crypt_pw.php
# waarbij de nieuwe hash als voorbeeld wordt : $2y$10$FmDz4r8V.PKc8qx8hLURUOokciAdc5QJFpaYS7aOnVh7S8tnAbTqq
# en gebruikersnaam $username = 'Gerrit'

# vervolgens met deze query in PHP invoegen

# $query = "INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`, `demo_hash_nieuw`)
# VALUES ($username, 'vervallen', $password));";

# In MySQL

INSERT INTO `demo_users` (`demo_username`, `demo_hash_md5`, `demo_hash_nieuw`)
	VALUES ('Gerrit', 'vervallen', '$2y$10$FmDz4r8V.PKc8qx8hLURUOokciAdc5QJFpaYS7aOnVh7S8tnAbTqq');

# En voor de zekerheid tonen

SELECT * FROM `demo_users` LIMIT 50;

# BESTAANDE GEBRUIKER DIE INLOGT

# zoek gebruiker met oude en nieuwe hash

SELECT * FROM `demo_users` WHERE `demo_username` = 'Jan';
SELECT 'Gebruiker Jan krijgt een bcrypt hash via PHP';
# IF `demo_hash_nieuw` <> "bijwerken"

#	test op nieuwe hash en ga door met inloggen,

# ELSE als demo_hash_nieuw` == "bijwerken"

#	test op md5 hash

#	IF md5 hash is okay
#		nieuwe hash maken van wachtwoord
#		bijvoorbeeld net deze functie
#		https://bytes.egestas.net/private/crypt_pw.php
#		doe een UPDATE met nieuw hash en de oude hash als "vervallen"
#		ga verder met inloggen
#	END IF

# of als oude hash of nieuwe hash onjuist, maak melding en begin opnieuw.

# volgorde van testen naar eigen inzicht

# Als op den duur ALLE wachtwoordhashes zijn aangepast
# dan kan veld `demo_hash_md5 worden gewist.

# voor demo werken we `demo_hash_nieuw` bij

# maar laten eerst alles zien
SELECT * FROM `demo_users`;

# dan uitvoeren
UPDATE `demo_users` SET
`demo_hash_md5` = 'vervallen',
`demo_hash_nieuw` = '$2y$10$IFcY16F5buHrbEbalZhEge5Dv.WQoDqXp2TCaBaxQeLB.xF0yQQHy'
WHERE `demo_username` = 'Jan';

UPDATE `demo_users` SET
`demo_hash_md5` = 'vervallen',
`demo_hash_nieuw` = '$2y$10$CBWFr5AmWsuyVBEoxSzfweg3Hmv6OEH00oU/d3Yu3qiQRx8v0wXhS'
WHERE `demo_username` = 'Piet';

UPDATE `demo_users` SET
`demo_hash_md5` = 'vervallen',
`demo_hash_nieuw` = '$2y$10$aiMItOqb/pz5SfVp45fSZuXn2JPsX/TwQvr53KM9d0ir2/PQG234u'
WHERE `demo_username` = 'Klaas';

# laten alles zien
SELECT * FROM `demo_users`;

# verwijderen veld `demo_hash_md5`
ALTER TABLE `demo_users` DROP `demo_hash_md5`;

# laten weer alles zien
SELECT * FROM `demo_users`;

# En we zijn weer bij