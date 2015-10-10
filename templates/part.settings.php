<div id="app-settings" active-table="active">
	<div id="CSVtableDIV">
		<textarea id="CSVcontent"></textarea>
		<textarea id="CSVcolumnCount"></textarea>
		<h2></h2>
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
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		><?php p($l->t('Backup and import')); ?></button>
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
			<input type="file" id="upload_csv" accept=".csv" >
		</div>
		<br>
	</div>
</div>
