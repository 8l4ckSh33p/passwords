<div id="PasswordsTable">

	<div id="PasswordsTableSearch">
		<label><?php p($l->t("Search table")); ?>: 
			<input type="text" id="search_text" class="tekst_veld" placeholder="<?php p($l->t("Search for")); ?>..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
		</label>
		<input id="search_clear" type="button" value="<?php p($l->t("Clear")); ?>">
	</div>

	<table id="PasswordsTableContent" class="sortable">
		<tr>
			<th id="column_website"><?php p($l->t("Website or company")); ?></th>
			<th class="sorttable_alpha"><?php p($l->t("Login name")); ?></th>
			<th class="sorttable_alpha"><?php p($l->t("Password")); ?></th>
			<th id="hide3"><?php p($l->t("Strength")); ?></th>
			<th class="sorttable_numeric" id="hide2"><?php p($l->t("Length")); ?></th>
			<th id="hide1">a-z</th>
			<th id="hide1">A-Z</th>
			<th id="hide1">0-9</th>
			<th id="hide1">!@#</th>
			<th id="hide2"><?php p($l->t("Creation date")); ?></th>
			<th class="sorttable_numeric" id="column_id">ID</th>
			<th id="column_delete"></th>
		</tr>

	</table>

	<br>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>column head</b> to sort the column.")); ?></p>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>user name</b> or a <b>password</b> to be able to copy it to the clipboard.")); ?></p>
	<p align="center"><?php print_unescaped($l->t("Click on a <b>website</b> to open it in a new tab.")); ?></p>
	<br>
	<p align="center"><?php print_unescaped($l->t("The <b>creation date</b> becomes <orange>orange after 150 days</orange> and <red>red after 365 days</red>.")); ?></p>
	<p align="center"><?php print_unescaped($l->t("The <b>strength value</b> is interpreted as") . " <red>" . strtolower($l->t("Weak")) .  "</red> (0-7), <orange>" . strtolower($l->t("Moderate")) . "</orange> (8-14) " . $l->t("or") . " <green>" . strtolower($l->t("Strong")) . "</green> (>= 15)."); ?></p>
	<p align="center"><?php print_unescaped($l->t("The <b>password colour</b> is determined by the password strength and the creation date (whichever comes first in weakness).")); ?></p>
	<br>

</div>

<script id="content-tpl" type="text/x-handlebars-template">

	{{#each passwords}}
		<tr>
			<td><div id="FieldLengthCheck">{{ website }}</div></td>
			<td><div id="FieldLengthCheck">{{ loginname }}</div></td>
			<td><div id="FieldLengthCheck">{{ pass }}</div></td>
			<td id="hide3">(strength)</td>
			<td id="hide2">(length)</td>
			<td id="hide1">(a-z)</td>
			<td id="hide1">(A-Z)</td>
			<td id="hide1">(0-9)</td>
			<td id="hide1">(!@#)</td>
			<td class="creation_date" id="hide2">{{ creation_date }}</td>
			<td class="row_id_td">{{ id }}</td>
			<td class="icon-delete"></td>
		</tr>
	{{/each}}
	
</script>

<div id="emptycontent">
	<div class="icon-passwords"></div>
	<h2><?php p($l->t("No passwords yet")); ?></h2>
	<p><?php p($l->t("Create some new passwords!")); ?></p> 
</div>
