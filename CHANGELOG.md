8.0.12
* Improvement for edit popup: title now shows active website and username
* CSS fix: overlay now actually overlays everything, including header
* Edited strength algorithm. Now emphasizes length better than previous versions (using length as a 6th exponential power)

8.0.11
* A new way of editing values with an interactive popup. This will let you use the password generator and is a more easy way of editing.
* Edited the backup function to make it an export function. These export files are fully compatible with KeePass, 1Password, LastPass and many other password services. Besides, Microsoft Excel can open the exported files natively as well.
* Small bugfixes

8.0.10
* Added possibility to import passwords from KeePass, 1Password, LastPass, SplashID or every other source, as long as it was exported as CSV. You can set the source columns yourself. 
 * Note: This is **not** less safe than putting in passwords one by one. This is Javascript only, so reading a CSV is practically very similar to typing in new passwords yourself.
* Added possibility in Personal settings to hide the columns |  a-z  |  A-Z  |  0-9  |  !@#  |

8.0.9
* Bugfix for Firefox: now clicking hidden values and the pencil actually works (`event` was not defined in JS)
* CSS fix: line-height doesn't change anymore when hovering a hidden password

8.0.8
* Bugfix: some variables were undefined, leading to errors in log
* Bugfix: hidden values now editable
* Hidden values are now viewable on mouse hover

8.0.7
* Added possibility to add notes to a password. These notes are encrypted just as strong as the passwords. 
* Added possibility to edit every field (website, full address, username, password and notes). Hover over a value and click on the pencil icon to change a value. 
* Added new icons for the form to add new passwords 
* No more page refreshing after creating, deleting (or editing) a row or value. All edits are done directly to the loaded table, so the page doesn't need to be refreshed.
* Other minor fixes

8.0.6

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

8.0.5
* Compatibility with ownCloud 8.1 (this release however works with 8.0 too!)

8.0.4
* Added German translation
* Completed Spanish translation
* Moved search field to navigation page, so this will stay visible when scrolling in a long list
* Mask passwords (click to view them). This is CSS-only for now, to prevent simple screenshot-theft of passwords. Will be JS-coded later, so passwords will actually load and be decrypted when '*****' is clicked
* Bug fixes (alignment of table heads, minor other things)

8.0.3
* Initial release, tested on ownCloud Server 8.0.*
