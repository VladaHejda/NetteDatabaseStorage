<?php

namespace Nette\Caching\Storages;

use Nette\Database\Context;
use Nette\Caching\Cache;

/**
 * Nette database storage.
 * Database table must contain columns `key` BIGINT, `value` BLOB. See cache.sql file.
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
		$this->db = $db;
		$this->table = $table;
	}


	/**
	 * @param string $key
	 * @return mixed
	 */
	public function read($key)
	{
		$key = $this->encodeKey($key);
		$data = $this->db->table($this->table)->where('key', $key)->fetch('value');
		if (isset($data['value'])) {
			return unserialize($data['value']);
		}
	}


	/**
	 * @param string $key
	 * @param mixed $data
	 * @param array $dependencies
	 */
	public function write($key, $data, array $dependencies)
	{
		$key = $this->encodeKey($key);
		$exists = $this->db->table($this->table)->where('key', $key)->update(['value' => serialize($data)]);

		if (!$exists) {
			$this->db->table($this->table)->insert([
				'key' => $key,
				'value' => serialize($data),
			]);
		}
	}


	/**
	 * @param string $key
	 */
	public function remove($key)
	{
		$key = $this->encodeKey($key);
		$this->db->table($this->table)->where('key', $key)->delete();
	}


	/**
	 * @param array $conditions
	 */
	public function clean(array $conditions)
	{
		if (!empty($conditions[Cache::ALL])) {
			$this->db->table($this->table)->delete();
		}
	}


	public function lock($key)
	{
		$key = $this->encodeKey($key);
	}


	protected function encodeKey($key)
	{
		// converts key into 8 bytes SIGNED integer
		return base_convert(substr(md5($key), 0, 16), 16, 10) - pow(2,63);
	}
}
