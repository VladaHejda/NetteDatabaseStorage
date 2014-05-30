<?php

namespace Tests\NetteDatabaseStorage;

use Nette\Database\Connection;

class Test extends \PHPUnit_Framework_TestCase
{

	/** @var Connection */
	public $connection;

	/** @var string */
	public $table = 'cache';

	/** @var \NetteDatabaseStorage */
	protected $storage;


	protected function setUp()
	{
		parent::setUp();

		if (!$this->connection instanceof Connection) {
			throw new \Exception(
				'Please, inject Nette\Database\Connection into Test case.'
			);
		}

		$this->connection->exec("
			CREATE TABLE IF NOT EXISTS `$this->table` (
				`ns` VARCHAR(32) NOT NULL COMMENT 'namespace',
				`key` VARCHAR(32) NOT NULL,
				`value` TEXT NOT NULL,
				PRIMARY KEY (`ns`, `key`),
				UNIQUE KEY (`ns`, `key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT '[temp] datové úložiště';
		");

		$this->storage = new \NetteDatabaseStorage($this->connection, $this->table);
	}


	public function test()
	{

	}
}
