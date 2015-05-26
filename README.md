# Passwords for ownCloud Server 8

This is a Password Manager for viewing, editing and generating passwords. 
You can insert and manage your own passwords or randomly generate new ones. Some characters are excluded upon password generation for readability purposes (1, I, l and B, 8 and o, O, 0). 

Passwords are stored into the ownCloud database with combined EtM (Encrypt-then-Mac(http://test.com)) and MCRYPT_BLOWFISH encryption (read Security part for details). 

NOTE 1: This is primarily intended as a password MANAGER, e.g. for a local ownCloud instance on your own WPA2 protected LAN. If you trust yourself enough as security expert, you can use this app behind an SSL secured server for a neat cloud solution. The app will be blocked if not accessed thru port 443 (https), which will result in your passwords not being loaded (decrypted) and shown. Also, make sure your server hasn't any kind of vulnerabilities (CSRF, XSS, SQL Injection, Privilege Escalation, Remote Code Execution, to name a few). 
NOTE 2: Generated passwords are in fact pseudo-generated (i.e. not using atmospheric noise), since only the Javascript Math.random-function is used, of which I think is randomly 'enough'. After generation of different types of characters (your choice to include lowercase, uppercase, numbers and/or reading marks, strength will be calculated), scrambling of these characters is done using the Fisher-Yates method (also known as Knuth, a de-facto unbiased shuffle algorithm). The script for creating passwords can be found in 'apps/passwords/js/script.js'.

# Installation

Download the latest release and copy the folder 'passwords' to /owncloud/apps/. Login as admin and enable the app.

#