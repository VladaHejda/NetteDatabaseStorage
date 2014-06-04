<?php

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configuration = \Nette\Neon\Neon::decode(file_get_contents(__DIR__ . '/config/.credentials.neon'));

// mysql
$dbConfig = $configuration['mysql-db'];
$connection = new \Nette\Database\Connection($dbConfig['dsn'], $dbConfig['user'], $dbConfig['password']);
$mysqlContext = new \Nette\Database\Context($connection, new \Nette\Database\Reflection\DiscoveredReflection($connection));

// postgreSQL
$dbConfig = $configuration['postgres-db'];
$connection = new \Nette\Database\Connection($dbConfig['dsn'], $dbConfig['user'], $dbConfig['password']);
$postgresContext = new \Nette\Database\Context($connection, new \Nette\Database\Reflection\DiscoveredReflection($connection));
