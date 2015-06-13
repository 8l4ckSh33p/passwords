# Passwords
#### for ownCloud Server 8
2015, Fallon Turner <fcturner@users.noreply.github.com>

## Introduction
This is a Password Manager for viewing, editing and generating passwords (see 'img/example.png' for a screenshot) in ownCloud. You can insert and manage your own passwords or randomly generate new ones. Some characters are excluded upon password generation for readability purposes (1, I, l and B, 8 and o, O, 0). Passwords are stored encrypted into the ownCloud database (read Security part for details). 

This app is primarily intended as a password MANAGER, e.g. for a local ownCloud instance on your own WPA2 protected LAN. If you trust yourself enough as security expert, you can use this app behind an SSL secured server for a neat cloud solution. The app will be blocked if not accessed thru port 443 (https), which will result in your passwords not being loaded (decrypted) and shown. Also, make sure your server hasn't any kind of vulnerabilities (CSRF, XSS, SQL Injection, Privilege Escalation, Remote Code Execution, to name a few). 

* Not happy with the encryption or the fact that this app exists? You're welcome to create your own :)
* Happily want to contribute to improve this app? You're welcome to contact me or create pull requests :)

The script for creating passwords can be found in 'js/script.js'.

## Security
### Password generation
Generated passwords are in fact pseudo-generated (i.e. not using atmospheric noise), since only the Javascript Math.random-function is used, of which I think is randomly 'enough'. After generation of different types of characters (your choice to include lowercase, uppercase, numbers and/or reading marks, strength will be calculated), scrambling of these characters is done using the [Fisher-Yates shuffle](http://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle) (also known as Knuth, a de-facto unbiased shuffle algorithm).
### Encryption (for storage in database)
All passwords (generated or your own) are stored encrypted in your ownCloud database.
* The keys are not used directly. Instead, it uses [key stretching](http://en.wikipedia.org/wiki/Key_stretching) which relies on [Password-Based Key Derivation Function 2](http://en.wikipedia.org/wiki/PBKDF2) (PBKDF2)
* The key used for encryption is unique for every encrypted block of text. The supplied key therefore becomes a "master key". This class therefore provides key rotation for cipher and authentication keys
* It uses [Encrypt-then-MAC](http://en.wikipedia.org/wiki/Authenticated_encryption#Approaches_to_Authenticated_Encryption) (EtM), which is a very good method for ensuring the authenticity of the encrypted data
* It uses mcrypt to perform the encryption using MCRYPT_BLOWFISH cyphers and MCRYPT_MODE_CBC for the mode. It's strong enough, and still fairly fast
* It hides the [Initialization vector](http://en.wikipedia.org/wiki/Initialization_vector) (IV)
* It uses a [timing-safe comparison](http://blog.ircmaxell.com/2014/11/its-all-about-time.html) function using [double Hash-based Message Authentication Code](http://en.wikipedia.org/wiki/Hash-based_message_authentication_code) (HMAC) verification of the source data

This all means: it's pretty safe :)

## Decryption (for pulling from database)
All passwords are encrypted with user-specific and server-specific keys. This means passwords can be decrypted:
* only by the user who created the password (so this user must be logged in), and
* only on the same ownCloud instance where the password was created in.

Admins are never able to decrypt passwords, since they cannot login as the user (assuming the administrator does not know the user's password).

## Website icons
All website icons in the password table are downloaded from a secured Google server when you load the page. Nothing fancy or unsafe (even using Google), it's just about icons. The icon for The White House's website for example (replace *whitehouse.gov* with your own domain to try): [https://www.google.com/s2/favicons?domain=www.whitehouse.gov](https://www.google.com/s2/favicons?domain=www.whitehouse.gov).

## Installation
Download the latest release and copy the folder 'passwords' to /owncloud/apps/. Login as admin and enable the app. The database table will be created automatically.

### Credits
I would like to thank Anthony Ferrara ([ircmaxell](http://careers.stackoverflow.com/ircmaxell)), for [teaching the world how to properly set up](http://stackoverflow.com/questions/5089841/two-way-encryption-i-need-to-store-passwords-that-can-be-retrieved/5093422#5093422) security in PHP.
