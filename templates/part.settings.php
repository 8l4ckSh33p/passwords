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
		<div id="app-settings-backup">
			<h3><?php p($l->t('Download Backup')); ?></h3>
			<button id="backupDL"><?php p($l->t('Download Backup')); ?></button>
			<p><?php p($l->t('Click to download a backup as an UNENCRYPTED plain text file')); ?>.</p>
			<hr>
		</div>
		<div id="app-settings-csv">
			<h3><?php p($l->t('Import CSV File')); ?></h3>
			<p><?php p($l->t('Choose a format and a select a file from disk')); ?>.</p>
			<label><input type="radio" id="owncloud-csv" name="csv_type" checked><?php p($theme->getName() . ' ' . $l->t('Passwords')); ?></label><br>
			<label><input type="radio" id="keepass-csv" name="csv_type">KeePass CSV (1.x)</label><br>
			<label><input type="radio" id="onepassword-csv" name="csv_type">1Password CSV</label><br>
			<label><input type="radio" id="lastpass-csv" name="csv_type">LastPass CSV</label><br>
			<label><input type="radio" id="splash-csv" name="csv_type">SplashID vID</label><br>
			<label><input type="radio" id="other-csv" name="csv_type"><?php p($l->t('Other')); ?> CSV</label>
			<div id="other-csv-list">
				<br>
				<p><strong><?php p($l->t("Fill in the column numbers")); ?>:</strong></p>
				<p id="subtitle"><?php p($l->t("Use 'x' to exclude a field")); ?></p>
				<div class="other-csv-list"><label><?php p($l->t('Website or company')); ?><input type="text" id="website-csv" value="1"></label></div>
				<div class="other-csv-list"><label><?php p($l->t('Login name')); ?><input type="text" id="login-csv" value="2"></label></div>
				<div class="other-csv-list"><label><?php p($l->t('Password')); ?><input type="text" id="password-csv" value="3"></label></div>
				<div class="other-csv-list"><label><?php p($l->t('Full URL (optional)')); ?><input type="text" id="url-csv" value="4"></label></div>
				<div class="other-csv-list"><label><?php p($l->t('Notes (optional)')); ?><input type="text" id="notes-csv" value="5"></label></div>
				<div class="other-csv-list"><label><?php p($l->t('File contains headers')); ?><input type="checkbox" id="headers-csv" checked></label></div>
			</div>
			<input type="file" id="upload_csv" accept=".csv" >
		</div>
		<br>
	</div>
</div>
