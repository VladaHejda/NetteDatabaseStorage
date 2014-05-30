<?php

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configuration = \Nette\Neon\Neon::decode(file_get_contents(__DIR__ . '/config/.credentials.neon'));
$db = $configuration['db'];
$connection = new \Nette\Database\Connection($db['dsn'], $db['user'], $db['password']);
$context = new \Nette\Database\Context($connection, new \Nette\Database\Reflection\DiscoveredReflection($connection));
$context->query("TRUNCATE TABLE `$db[table]`");

$storage = new \Nette\Caching\Storages\DatabaseStorage($context, $db['table']);
