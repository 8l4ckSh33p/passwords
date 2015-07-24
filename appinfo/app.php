<?php

use OCP\AppFramework\App;

$app = new App('passwords');
$container = $app->getContainer();

$l = \OCP\Util::getL10N('passwords');

\OCP\App::registerAdmin('passwords', 'templates/admin.settings');
\OCP\App::registerPersonal('passwords', 'templates/personal.settings');

$urlGenerator = $container->query('OCP\IURLGenerator');
$l10n = $container->query('OCP\IL10N');

\OCP\App::addNavigationEntry(array(
		'id' => 'passwords',
		'order' => 9999,
		'href' => $urlGenerator->linkToRoute('passwords.page.index'),
		'icon' => $urlGenerator->imagePath('passwords', 'app.svg'),
		'name' => $l10n->t('Passwords'),
));
