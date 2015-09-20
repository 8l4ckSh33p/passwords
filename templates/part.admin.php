<div class="section" id="passwords-admin">
	<h2><?php p($l->t('Passwords')); ?></h2>

	<br>
	<div>
		<h3><?php p($l->t('Security')); ?></h3>

		<label>
			<input class="check" type="checkbox" id="https_check"> <?php p($l->t('Block app when not connected to %s using a secured connection', array($theme->getName()))); ?>
		</label>
		<p class="descr">
			<em class="https_warning"><?php p($l->t('Turning this off is HIGHLY DISCOURAGED')); ?>.</em>
		</p>

		<label>
			<input class="check" type="checkbox" id="backup_allowed"> <?php p($l->t('Allow users to download a backup as an unencrypted, plain text file')); ?>
		</label>
	</div>

	<br>
	<div>
		<h3><?php p($l->t('Website icons')); ?></h3>
		<label>
			<input class="check" type="checkbox" id="icons_allowed"> <?php p($l->t('Allow website icons')); ?>
		</label>
		<p class="descr">
			<em><?php p($l->t('This will help users finding a website they are looking for in their list and it looks rather nice too, but it will send your IP address to another server')); ?>.</em>
		</p>
		<div>
			<label>
				<input class="radio" type="radio" id="ggl_value" name="icons_service"> <?php p($l->t('Use Google')); ?>
			</label>
			<br>
			<label>
				<input class="radio" type="radio" id="ddg_value" name="icons_service"> <?php p($l->t("Use DuckDuckGo")); ?>
			</label>
			<p class="descr radiotext">
				<?php p($l->t("Google DOES track your moves. Use DuckDuckGo preferably, since they don't")); ?>: <a class="linkDDG" href="http://donttrack.us" target="_blank">http://donttrack.us</a>.
			</p>
		</div>
	</div>

	<br>
	<div>
		<h3><?php p($l->t('Colour of creation date')); ?></h3>
		<p><?php p($l->t('When the creation date of a password exceeds the limit for the green colour, the password will become orange or red too')); ?>.</p>
		<label> 
			<green><?php p($l->t('Green') . ': 0 ' . $l->t('to')); ?> <input type="text" id="days_orange" class="fieldDays" value=""> <?php p($l->t('days')); ?></green>
		</label>
		<br>
		<label> 
			<orange id="daysOrange"></orange> <input type="text" id="days_red" class="fieldDays" value=""> <orange><?php p($l->t('days')); ?></orange>
		</label>
		<br>
		<label>
			<red id="daysRed"></red>
		</label>
	</div>

	<br>

	<span class="msg-passwords"></span>

</div>
