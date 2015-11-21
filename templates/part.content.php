<?php
$days_orange = \OCP\Config::getAppValue('passwords', 'days_orange', '150');
$days_red = \OCP\Config::getAppValue('passwords', 'days_red', '365');
?>

<div id="cleartrashbin">
	<input type="button" id="delete_trashbin" value="<?php p($l->t("Delete all items in trash bin")); ?>">
</div>

<div id="PasswordsTable">

	<table id="PasswordsTableContent" class="sortable">
		<tr>
			<th id="column_website"><?php p($l->t("Website or company")); ?></th>
			<th id="FieldLengthCheck" class="sorttable_alpha"><?php p($l->t("Login name")); ?></th>
			<th id="FieldLengthCheck" class="sorttable_alpha"><?php p($l->t("Password")); ?></th>
			<th id="hide3"><?php p($l->t("Strength")); ?></th>
			<th class="hide_attributes sorttable_numeric" id="hide1"><?php p($l->t("Length")); ?></th>
			<th class="hide_attributes" id="hide1">a-z</th>
			<th class="hide_attributes" id="hide1">A-Z</th>
			<th class="hide_attributes" id="hide1">0-9</th>
			<th class="hide_attributes" id="hide1">!@#</th>
			<th id="hide2"><?php p($l->t("Last changed")); ?></th>
			<th id="hide_always">ID</th>
			<th id="hide_always">User-ID</th>
			<th id="hide_always">Address</th>
			<th id="column_notes"></th>
			<th id="column_delete"></th>
			<th id="hide_always">Deleted</th>
			<th id="column_sidebar"></th>
			<!-- <th id="column_share"></th> -->
		</tr>

	</table>

	<br>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>column head</b> to sort the column.")); ?></p>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>user name</b> or a <b>password</b> to be able to copy it to the clipboard.")); ?></p>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>website</b> to open it in a new tab.")); ?></p>
	<br>
	<p align="center"><?php print_unescaped($l->t("The <b>password date</b> becomes <orange>orange after %s days</orange> and <red>red after %s days</red>.", array($days_orange, $days_red))); ?></p>
	<p align="center"><?php print_unescaped($l->t("The <b>strength value</b> is interpreted as") . " <red>" . strtolower($l->t("Weak")) .  "</red> (0-7), <orange>" . strtolower($l->t("Moderate")) . "</orange> (8-14) " . $l->t("or") . " <green>" . strtolower($l->t("Strong")) . "</green> (>= 15)."); ?></p>
	<p align="center"><?php print_unescaped($l->t("The <b>password colour</b> is determined by the password strength and date (whichever comes first in weakness).")); ?></p>
	<br>

</div>

<script id="content-tpl" type="text/x-handlebars-template">

	{{#each passwords}}
		<tr>
			<td><div id="FieldLengthCheck">{{ website }}</div></td>
			<td><div id="FieldLengthCheck">{{ loginname }}</div></td>
			<td><div id="FieldLengthCheck">{{ pass }}</div></td>
			<td id="hide3">(strength)</td>
			<td class="hide_attributes" id="hide1">(length)</td>
			<td class="hide_attributes" id="hide1">(a-z)</td>
			<td class="hide_attributes" id="hide1">(A-Z)</td>
			<td class="hide_attributes" id="hide1">(0-9)</td>
			<td class="hide_attributes" id="hide1">(!@#)</td>
			<td class="creation_date" id="hide2">{{ creation_date }}</td>
			<td class="hide_always">{{ id }}</td>
			<td class="hide_always">{{ user_id }}</td>
			<td class="hide_always">{{ address }}</td>
			<td class="icon-notes"><span>{{ notes }}</span></td>
			<td class="icon-delete"></td>
			<td class="hide_always">{{ deleted }}</td>
			<td class="icon-info"></td>
			<!--<td class="icon-share"></td>-->
		</tr>
	{{/each}}
	
</script>

<div id="emptycontent">
	<div class="icon-passwords"></div>
	<h2><?php p($l->t("No passwords yet")); ?></h2>
	<p><?php p($l->t("Create some new passwords!")); ?></p> 
</div>
<div id="emptytrashbin">
	<div class="icon-delete"></div>
	<h2><?php p($l->t("Empty trash bin")); ?></h2>
	<p><?php p($l->t("Deleted passwords will be shown here!")); ?></p> 
</div>

<div id="idleTimer">
	<p id="countSec"></p>
	<p id="explnSec"><?php p($l->t("You will be logged off automatically when this countdown reaches 0")); ?>.</p>
	<div id="outerRing"></div>
</div>
