
<ul>
<li class="with-counter active">
	<a href="#"><?php p($l->t("Number of passwords")); ?></a>
	<div class="app-navigation-entry-utils">
		<ul>
			<li class="app-navigation-entry-utils-counter menu_passwords_active">0</li>
		</ul>
	</div>
</li>

<br><br>

<div id="add_password_div">
	<h3><?php p($l->t("Add new password")); ?></h3>
	<input type="text" id="new_website" class="tekst_veld" placeholder="<?php p($l->t("site.com or Name Inc.")); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
	<input type="text" id="new_username" class="tekst_veld" placeholder="<?php p($l->t("Login name or e-mail")); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
	<input type="text" id="new_password" class="tekst_veld" placeholder="<?php p($l->t("Password")); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
	<div id="generate-password">
			<p id="generate-strength"></p>
		<div id="generate-passwordtools">
			<label>
				<input class="check" type="checkbox" id="gen_lower" checked> <?php p($l->t("Lowercase characters")); ?>
			</label>
			<br>
			<label>
				<input class="check" type="checkbox" id="gen_upper" checked> <?php p($l->t("Uppercase characters")); ?>
			</label>
			<br>
			<label>
				<input class="check" type="checkbox" id="gen_numbers" checked> <?php p($l->t("Numbers")); ?>
			</label>
			<br>
			<label>
				<input class="check" type="checkbox" id="gen_special" checked> <?php p($l->t("Punctuation marks")); ?>
			</label>
			<br>
			<label>
				<input type="text" id="gen_length" value="25"> <?php p($l->t("characters")); ?>
			</label>
			<br>
		</div>
		<input id="new_generate" type="button" class="search_clear_shown" value="<?php p($l->t("Generate password")); ?>">
	</div>
	<input id="new_password_add" type="button" value="<?php p($l->t("Add password")); ?>">
</div>

<ul></ul>