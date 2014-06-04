<?php

require 'bootstrap.php';

use Tester\Assert;

$testTempTable = 'NETTEDATABASESTORAGE_DEPENDENCIES_TEST';

$context->query("DROP TABLE IF EXISTS $testTempTable");
$context->query("
	CREATE TABLE $testTempTable (
		`key` INT UNSIGNED NOT NULL,
		`value` TEXT NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE=InnoDB
");

$storage = new \Nette\Caching\Storages\DatabaseStorage($context, $testTempTable);

// write some data
$storage->write('mafioso', 'Vincent Vega', []);
$storage->write('weapon', 'gun', []);

// clean
$storage->clean([\Nette\Caching\Cache::ALL => TRUE]);
Assert::null($storage->read('mafioso'));
Assert::null($storage->read('weapon'));

$context->query("DROP TABLE $testTempTable");
