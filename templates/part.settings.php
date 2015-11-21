<div id="app-settings" active-table="active" session-timeout="<?php p(OC::$server->getConfig()->getSystemValue('session_lifetime', 60)) ?>">
	<textarea id="session_lifetime" disabled="true"></textarea>
	<div id="CSVtableDIV">
		<textarea id="CSVcontent"></textarea>
		<textarea id="CSVcolumnCount"></textarea>
		<p></h2>
		<h3><?php p($l->t('Select options')); ?>:</h3>
		<div id="CSVbuttons">
			<button id="CSVheadersBtn"><?php p($l->t('File contains headers')); ?></button>
			<button id="CSVquotationmarksBtn"><?php p($l->t('Values are seperated by quotation marks')); ?></button>
			<button id="CSVescapeslashBtn"><?php p($l->t('Characters %s and %s need to be escaped', array('\\', '"'))); ?></button>
			<button id="CSVsplit_rnBtn"><?php p($l->t('Lines are split on')); ?> \r\n</button>
		</div>
		<br><br><br>
		<h3 id="CSVpreviewTitle"><?php p($l->t('Preview')); ?> :</h3>
		<div id="CSVtableScroll">
			<table id="CSVtable">
				<!-- TABLE WILL BE POPULATED WITH JS -->
			</table>
		</div>
		<button id="selectAll"><?php p($l->t('Select all')); ?></button>
		<button id="selectNone"><?php p($l->t('Select none')); ?></button>
		<div id="CSVerrorfield"></div>
		<button id="importCancel"><?php p($l->t('Cancel')); ?></button>
		<button id="importStart"><?php p($l->t('Import')); ?></button>
	</div>
	<div id="CSVprogressDIV">
		<p id="CSVprogressTitle"><?php p($l->t('Import')); ?></p>
		<input id="CSVprogressActive"/>
		<input id="CSVprogressTotal"/>
		<p id="CSVprogressText1">0 / 0</p>
		<p id="CSVprogressText2">0 / 0</p>
		<div id="CSVprogressBar">
			<div id="CSVprogressDone"></div>
		</div>
	</div>
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		><?php p($l->t('Backup and import')); ?></button>
	</div>
	<div id="app-settings-content">
		<div id="app-settings-trashall">
			<button id="trashAll"><?php p($l->t('Move all to trash')); ?></button>
			<p><?php p($l->t('Click to move all active passwords to the trash')); ?>.</p>
			<hr>
		</div>
		<div id="app-settings-backup">
			<h3><?php p($l->t('Download Backup')); ?></h3>
			<button id="backupDL"><?php p($l->t('Download Backup')); ?></button>
			<p><?php p($l->t('Click to download a backup as an UNENCRYPTED plain text file')); ?>.</p>
			<hr>
		</div>
		<?php if (\OCP\Config::getAppValue('passwords', 'backup_allowed', 'false') == 'false') { ?>
			<div id="app-settings-backup_disallowed">
				<h3><?php p($l->t('Download Backup')); ?></h3>
				<p><?php p($l->t('Your administrator does not allow you to download backups')); ?>.</p>
				<hr>
			</div>
		<?php } ?>
		<div id="app-settings-csv">
			<h3><?php p($l->t('Import CSV File')); ?></h3>
			<input type="file" id="upload_csv" accept=".csv" >
		</div>
		<br>
	</div>
</div>
