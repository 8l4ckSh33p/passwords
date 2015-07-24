<?php	
	$user = \OC::$server->getUserSession()->getUser()->getUID();
?>

<div id="app-settings" backup-title="<?php p($l->t('Downloaded from %s by %s', array($theme->getName(), $user))); ?>">
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		></button>
	</div>
	<div id="app-settings-content">
		<button id="backupDL"><?php p($l->t('Download Backup')); ?></button>
		<p><?php p($l->t('Instead of copying and pasting, click this button to download a backup of the website, user name and passwords columns as an UNENCRYPTED plain text file')); ?>.</p>
	</div>
</div>
