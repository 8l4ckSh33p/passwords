<div class="section" id="passwords-personal">
	<h2><?php p($l->t('Passwords')); ?></h2>

	<div id="icons_show_div">
		<label>
			<input class="check" type="checkbox" id="icons_show"> <?php p($l->t('Show website icons')); ?>
		</label>
		<br><br>
	</div>
	
	<div>
		<label>
			<input class="check" type="checkbox" id="hide_usernames"> <?php p($l->t('Hide usernames')); ?>
		</label>
		<br>
		<label>
			<input class="check" type="checkbox" id="hide_passwords"> <?php p($l->t('Hide passwords')); ?>
		</label>
		<p>
			<?php p($l->t("This will show values as '*****', so you will need to click on a value to actually view it. This is useful to prevent others from making screenshots or taking photos of your password list")); ?>.
		</p>
		<br>
	</div>

	<div>
		<label>
			<input class="check" type="checkbox" id="hide_attributes"> <?php p($l->t('Hide columns') . ": '" . strtolower($l->t('Strength')) . "' & '" . strtolower($l->t('Last changed')) . "'"); ?>
		</label>
		<br><br>
	</div>

	<div>
		<label>
			<input class="check" type="checkbox" id="timer_bool"> <?php p($l->t('Use inactivity countdown')); ?>
		</label>
		<label>
			<input type="text" id="timer" value="0"> <em id="timersettext"> <?php p($l->t('seconds')); ?> </em>
		</label>
		<p>
			<?php p($l->t("This will put a timer on the lower right of the screen, which resets on activity.") . " " . $l->t("You will be logged off automatically when this countdown reaches 0")); ?>.
		</p>
		<p>
			<?php p($l->t("Setting a countdown will log you off too when your session cookie ends (set to %s seconds by your administrator)", OC::$server->getConfig()->getSystemValue('session_lifetime', 60))); ?>.
		</p>
	</div>

	<span class="msg-passwords"></span>

</div>
