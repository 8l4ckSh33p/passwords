####16.1
* Fixed a bug for ownCloud 8.2 and higher

####16
* Added a countdown timer, which can be set by users. When the timer reaches 0, the user will be logged off (will show a message first). Valid values are 10-3599 seconds. The countdown timer resets on activity in the passwords app. When a timer is set, the user will be logged off too when the session cookie ends (if set by admin in config.php, will else be 60 seconds and not the default 15 days).
* Added a sidebar with info about the password
* Added a progress bar for importing passwords
* Added admin option to disable the browsers context menu on this app
* Added a button to move all active passwords to trash
* Removed info columns 'length', 'a-z', 'A-Z', '0-9' and '!@#' (now available in the sidebar)
* Edited settings to hide 'strength' and 'last changed' columns, instead of a-z, A-Z, 0-9 and !@#
* Replaced alerts and confirmation popups by native OC dialogs
* Fix for emptying trash on Firefox (#80)
* Fix for saving a website URL on Firefox (#91)
* Fix for importing huge CSV files (i.e. a lot of passwords) (#89)
* Fix for checking for website column on CSV import (#90)
* Fix for importing values with double quotation marks and removed unneeded extra CSV column (#86)
* Fix for (multiline) notes sometimes not being imported (#85)
* Updated language files for English, Spanish, Dutch. Want to do an update for your own language? Look at the changes at [TRANSLATION.js](https://github.com/fcturner/passwords/commit/7f9428bac14fbfb8f866eff59d7b0efa1899967d)

####15
* Changed version numbering: 8.0.15 is replaced by 15, since future release may support more versions than OC8 only, and it suggested an ownCloud version more than an app version
* Added new CSV import screen, with live preview
* Added Italian language support
* Added note for users when admin has blocked downloading of back-ups
* Fix for editing values containing `<` or `>`
* Language update? Look at [TRANSLATION.js](TRANSLATION.js)

####8.0.14
* Added button in trash to permanently delete all passwords in trash bin
* Auto-select on hover of passwords and usernames, with notification text to copy them with Ctrl+C or Cmd+C (detects system automatically). This is disabled for Android and iOS (of course)
* Renamed *Creation date* to *Last changed*
* Fix for CSV files containing double quotation marks `"` or backslash `\` in values 
* Fix for CSV files containing notes with multiple lines
* Fix for CSV files containing a file extension in uppercase
* Fix for height of popup title

####8.0.13
* Added search icon in search bar, saving another non-whitespaced line on navigation pane
* Added auto-save in settings, both admin and personal (no more button clicking)
* Totally rewrote (and fixed) the import function (for CSV files), with added error description for every possible error
* Fix for usernames and passwords containing HTML-characters like < or >
* Fix for editing a website value which became lowercase even when not a valid URL
* Fix for icon not showing on empty trash bin
* CSS fix for button texts

####8.0.12
* Added trash bin: deleted password are now moved to the trash bin, so they can be reverted or permanently deleted (this triggers the ownCloud update screen, since a mandatory database edit to the passwords table will be made)
* Added option to save old values to the trash bin when editing a website, username or password, so you can look them up when needed
* Edited strength algorithm. Now emphasizes length better by adding the rounded value of n<sub></sub><sup>x</sup> / 10<sup>x + 1</sup> to the calculated strength, where `n` stands for the amount of characters (i.e. length) and `x` is the power. By using `x = 6`, this gives a more accurate value when passwords are longer than +/- 15 characters and grows exponentially.
* Added Catalan language, including date format
* Improvement: title of edit popup now shows active website with username in subtitle
* Improvement: read user language from html tag, instead of language files
* Fix: date format for foreign languages (i.e. undefined in this app) 
* Fix: CSV files with empty lines aren't considered invalid anymore (so KeePass import should work again!)
* Fix: overlay now actually overlays everything, including header
* Fix: align popup vertically, independant of its content and height
* Fix: no more jumping widths when hovering values
* Small other bugfixes
* Add you own language! Strings all sorted out here: [TRANSLATION.js](TRANSLATION.js).

####8.0.11
* A new way of editing values with an interactive popup. This will let you use the password generator and is a more easy way of editing.
* Edited the backup function to make it an export function. These export files are fully compatible with KeePass, 1Password, LastPass and many other password services. Besides, Microsoft Excel can open the exported files natively as well.
* Small bugfixes

####8.0.10
* Added possibility to import passwords from KeePass, 1Password, LastPass, SplashID or every other source, as long as it was exported as CSV. You can set the source columns yourself. 
 * Note: This is **not** less safe than putting in passwords one by one. This is Javascript only, so reading a CSV is practically very similar to typing in new passwords yourself.
* Added possibility in Personal settings to hide the columns |  a-z  |  A-Z  |  0-9  |  !@#  |

####8.0.9
* Bugfix for Firefox: now clicking hidden values and the pencil actually works (`event` was not defined in JS)
* CSS fix: line-height doesn't change anymore when hovering a hidden password

####8.0.8
* Bugfix: some variables were undefined, leading to errors in log
* Bugfix: hidden values now editable
* Hidden values are now viewable on mouse hover

####8.0.7
* Added possibility to add notes to a password. These notes are encrypted just as strong as the passwords. 
* Added possibility to edit every field (website, full address, username, password and notes). Hover over a value and click on the pencil icon to change a value. 
* Added new icons for the form to add new passwords 
* No more page refreshing after creating, deleting (or editing) a row or value. All edits are done directly to the loaded table, so the page doesn't need to be refreshed.
* Other minor fixes

####8.0.6

* Thanks to all contributors on GitHub, this is a rather big update. So thanks, you all!
  * Downloadable backup
  * Hiding of usernames and passwords
  * Added optional URL-field

* Introducing settings!
  * Admin setting: check for secure connection at start and block app if there isn't one (leave this one checked preferably!)
  * Admin setting: allow/disallow downloading of backups (because they are not secure)
  * Admin setting: allow/disallow showing website icons (since using this service, the IP address wil be sent to another server)
  * Admin setting: service used for website icons: DuckDuckGo (default) or Google
  * Admin setting: amount of days before the creation date colours orange or red
  * User setting: show/hide website icons
  * User setting: show/hide usernames
  * User setting: show/hide passwords
* 'Secure connection check' at start now checks for 'forcesll => true' in config.php too, fixing a false-positive error for people using (external) SSL extensions
* Fixed length for search fields
* Minor bug fixes and code cleaning
* *NOTE: this version works on 8.0.** *and 8.1.**

####8.0.5
* Compatibility with ownCloud 8.1 (this release however works with 8.0 too!)

####8.0.4
* Added German translation
* Completed Spanish translation
* Moved search field to navigation page, so this will stay visible when scrolling in a long list
* Mask passwords (click to view them). This is CSS-only for now, to prevent simple screenshot-theft of passwords. Will be JS-coded later, so passwords will actually load and be decrypted when '*****' is clicked
* Bug fixes (alignment of table heads, minor other things)

####8.0.3
* Initial release, tested on ownCloud Server 8.0.*
