<?php

require '../bootstrap.php';

use Tester\Assert;

$testTempTable = 'NETTEDATABASESTORAGE_DEPENDENCIES_TEST';

$postgresContext->query("DROP TABLE IF EXISTS $testTempTable");
$postgresContext->query("
	CREATE TABLE $testTempTable (
		key BIGINT NOT NULL,
		value TEXT NOT NULL,
		PRIMARY KEY (key)
	)
");

$storage = new \Nette\Caching\Storages\DatabaseStorage($postgresContext, $testTempTable);

// write some data
$storage->write('mafioso', 'Vincent Vega', []);
$storage->write('weapon', 'gun', []);

// clean
$storage->clean([\Nette\Caching\Cache::ALL => TRUE]);
Assert::null($storage->read('mafioso'));
Assert::null($storage->read('weapon'));

$postgresContext->query("DROP TABLE $testTempTable");
