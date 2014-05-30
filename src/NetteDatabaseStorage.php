<?php

namespace Nette\Caching\Storages;

use Nette\Database\Context;

/**
 * Mysql database storage.
 * Database table must contain columns `key` VARCHAR(64), `value` TEXT. See cache.sql file.
 */
class DatabaseStorage extends \Nette\Object implements \Nette\Caching\IStorage
{

	/** @var Context */
	private $db;

	/** @var string */
	private $table;


	/**
	 * @param Context $db
	 * @param string $table
	 * @throws \Nette\MemberAccessException
	 */
	public function __construct(Context $db, $table = 'cache')
	{
		if ($table !== \Nette\Utils\Strings::webalize($table, '_')) {
			throw new \Nette\MemberAccessException("Table name must be alphanumeric string, '$table' given.");
		}

		$this->db = $db;
		$this->table = $table;
	}


	/**
	 * @param string $key
	 * @return mixed
	 */
	public function read($key)
	{
		$data = $this->db->table($this->table)->where('key', $key)->fetch('value');
		if (isset($data['value'])) {
			return unserialize($data['value']);
		}
	}


	/**
	 * @todo $dependencies
	 */
	public function write($key, $data, array $dependencies)
	{
		$exists = $this->db->table($this->table)->where('key', $key)->update(['value' => serialize($data)]);

		if (!$exists) {
			$this->db->table($this->table)->insert([
				'key' => $key,
				'value' => serialize($data),
			]);
		}
	}


	public function remove($key)
	{
		$this->db->table($this->table)->where('key', $key)->delete();
	}


	/**
	 * @todo FIX!
	 */
	public function clean(array $conditions)
	{
		$this->db->query("TRUNCATE TABLE `$this->table`");
	}


	public function lock($key)
	{
	}
}
