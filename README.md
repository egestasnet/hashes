# hashes
Change MySQL/Mariadb columns when changing password hashes

Wanneer wachtwoordhash methoden verouderd zijn, bijvoorbeeld omdat er nieuwe hashmethoden zijn ontwikkeld, is het soms nodig om de hashes te veranderen in een database met gebruikersgegevens.

Je kunt dezelfde hash column gebruiken voor de nieuwe hash. Let er wel op dat de veldlengte eventueel moet worden aangepast.

Je kunt ook een nieuwe hash column in het record aanbrengen en dan gedurende een periode nieuwe hashes aanmaken voor nieuwe gebruikers of als gebruikers inloggen.

Dit voorbeeld maakt gebruik van de tweede oplossing.

Deze DEMO (als SQL en als PHP) doet het volgende :

1. Het maakt een database aan.
2. Het maakt een table aan.
3. Het voegt 3 gebruikers toe met een MD5 hash.
4. Het voegt een hash column toe
5. Het vult die column met een standaard tekst
6. Het voegt later een 'nieuwe' gebruiker met een bcrypt hash toe.
7. Het update de bestaande gebruikers naar de nieuwe becrypt hash.
8. Het verwijdert de MD5 column.

De code om de oude hash te controleren en een nieuwe hash aan te maken, moet je integreren in de bestaande software van aanmaken, inloggen en veranderen van wachtwoord.

Na verloop van tijd is het aan te bevelen om te controleren of van iedereen de wachtwoordhash is aangepast.

Je kunt dan met een query kijken of de waarde van het MD5 veld 'vervallen' is of dat het nieuwe hashveld de waarde 'bijwerken' heeft.

Eventueel kan je die gebruikers een e-mail sturen met het verzoek om even opnieuw in te loggen.

Als iedereen dan heeft meegewerkt, kan je het MD5 veld verwijderen.

Daarna krijgen gebruikers een melding dat hun wachtwoord niet meer geldig is en moeten ze een nieuw wachtwoord maken.

Over MD5 hashes.

https://nl.wikipedia.org/wiki/MD5

Dat onderzoek heeft dus betrekking op bestanden, niet op wachtwoorden.

"MD5 can be used as a checksum to verify data integrity against unintentional corruption."

Eerst maar eens zoeken :

https://www.google.com/search?q=md5+collisions

https://www.researchgate.net/publication/222578007_MD5_collisions_and_the_impact_on_computer_forensics

"they had successfully generated two files with different contents that had the same MD5 hash"

https://repo.zenk-security.com/Cryptographie%20.%20Algorithmes%20.%20Steganographie/MD5%20Collisions.pdf

======

When password hash methods are outdated, for example because new hash methods have been developed, it is sometimes necessary to change the hashes to a database of user data.

You can use the same hash column for the new hash. Please note that the field length may have to be adjusted.

You can also add a new hash column to the record and then create new hashes for new users for a period of time or as users log in.

This example uses the second solution.

This DEMO (as SQL and as PHP) does the following:

1. It creates a database.
2. It creates a table.
3. It adds 3 users with an MD5 hash.
4. It adds a hash column.
5. It fills that column with a standard text.
6. It later adds a 'new' user with a bcrypt hash.
7. It updates the existing users to the new becrypt hash.
8. It removes the MD5 column.

The code to check the old hash and create a new hash must be integrated into the existing software of creating, logging in and changing password.

Over time, it is recommended to check that everyone's password hash has been adjusted.

You can then use a query to see if the value of the MD5 field has 'expired' or whether the new hash field has the value 'redate'.

Optionally, you can send those users an email with the request to log in again.

If everyone has cooperated, you can delete the MD5 field.

After that, users are notified that their password is no longer valid and must create a new one.

About MD5 hashes.

https://en.wikipedia.org/wiki/MD5

So that research relates to files, not passwords.

"MD5 can be used as a checksum to verify data integrity against unintentional corruption."

First, let's search:

https://www.google.com/search?q=md5+collisions

https://www.researchgate.net/publication/222578007_MD5_collisions_and_the_impact_on_computer_forensics

"they had successfully generated two files with different contents that had the same MD5 hash"

https://repo.zenk-security.com/Cryptographie%20.%20Algorithmes%20.%20Steganographie/MD5%20Collisions.pdf

