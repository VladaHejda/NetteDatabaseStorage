<?php

require '../bootstrap.php';

use Tester\Assert;

$testTempTable = 'NETTEDATABASESTORAGE_CRUD_TEST';

$mysqlContext->query("DROP TABLE IF EXISTS $testTempTable");
$mysqlContext->query("
	CREATE TABLE $testTempTable (
		`key` BIGINT NOT NULL,
		`value` BLOB NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE=InnoDB
");

$storage = new \Nette\Caching\Storages\DatabaseStorage($mysqlContext, $testTempTable);

// read unknown
Assert::null($storage->read('name'));

// write and read
$storage->write('name', 'John Doe', []);
Assert::equal('John Doe', $storage->read('name'));

// re-write
$storage->write('name', 'Molly', []);
Assert::equal('Molly', $storage->read('name'));

// write another
$storage->write('age', 24, []);
Assert::equal(24, $storage->read('age'));

// write array
$items = ['cheese', 'apple', 'steak'];
$storage->write('items', $items, []);
Assert::equal($items, $storage->read('items'));

// remove
$storage->remove('name');
Assert::null($storage->read('name'));

$mysqlContext->query("DROP TABLE $testTempTable");
