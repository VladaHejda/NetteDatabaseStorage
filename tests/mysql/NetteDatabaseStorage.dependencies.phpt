<?php

require '../bootstrap.php';

use Tester\Assert;

$testTempTable = 'NETTEDATABASESTORAGE_DEPENDENCIES_TEST';

$mysqlContext->query("DROP TABLE IF EXISTS $testTempTable");
$mysqlContext->query("
	CREATE TABLE $testTempTable (
		`key` INT NOT NULL,
		`value` TEXT NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE=InnoDB
");

$storage = new \Nette\Caching\Storages\DatabaseStorage($mysqlContext, $testTempTable);

// write some data
$storage->write('mafioso', 'Vincent Vega', []);
$storage->write('weapon', 'gun', []);

// clean
$storage->clean([\Nette\Caching\Cache::ALL => TRUE]);
Assert::null($storage->read('mafioso'));
Assert::null($storage->read('weapon'));

$mysqlContext->query("DROP TABLE $testTempTable");
