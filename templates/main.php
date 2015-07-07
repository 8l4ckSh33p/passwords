<?php

	function isSecure() {
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		if (false !== strpos($url,'d=1')) {
			OC_Log::write('passwords', 'Passwords app accessed without secure connection.', OC_Log::WARN);
		    return true;
		}

	  	return
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| $_SERVER['SERVER_PORT'] == 443
		|| \OC_Config::getValue('forcessl', '');
	};

	style('passwords', 'style');

	// check if secure (https)
	if (isSecure()) {

		script('passwords', 'handlebars');
		script('passwords', 'script');
		script('passwords', 'sorttable');

?>

	<div id="app">
		<div id="app-navigation">
			<?php print_unescaped($this->inc('part.navigation')); ?>
			<?php print_unescaped($this->inc('part.settings')); ?>
		</div>

		<div id="app-content">
			<div id="app-content-wrapper">
				<?php print_unescaped($this->inc('part.content')); ?>
			</div>
		</div>
	</div>

<?php } else {
		
		OC_Log::write('passwords', 'Passwords app blocked; no secure connection.', OC_Log::ERROR);
?>

		<div id="app">

			<div id="app-content">
				<div id="app-content-wrapper">
					<?php print_unescaped($this->inc('part.blocked')); ?>
				</div>
			</div>
		</div>

	<?php } ?>
