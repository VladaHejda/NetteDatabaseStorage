<?php

/**
 * Mysql database storage.
 * Database table must contain columns `ns` VARCHAR(32), `key` VARCHAR(32), `value` VARCHAR(256) or TEXT.
 */
class NetteDatabaseStorage extends \Nette\Object implements \Nette\Caching\IStorage
{

	/** @var bool whether cache data per run (into class variable) */
	public $useInternalCache = TRUE;

	/** @var \Nette\Database\Connection */
	private $db;

	/** @var string */
	private $dbName;

	/** @var array */
	private $varCache = [];


	/**
	 * @param \Nette\Database\Connection
	 * @param string
	 * @throws \Nette\MemberAccessException
	 */
	public function __construct(\Nette\Database\Connection $db, $dbName = 'cache')
	{
		if ($dbName !== \Nette\Utils\Strings::webalize($dbName, '_')) {
			throw new \Nette\MemberAccessException("Database name must be alphanumeric string, '$dbName' given.");
		}

		$this->db = $db;
		$this->dbName = $dbName;
	}


	/**
	 * @param string $key
	 * @return mixed
	 */
	public function read($key)
	{
		if ($this->useInternalCache && array_key_exists($key, $this->varCache)) {
			return $this->varCache[$key];
		}

		list($ns, $key) = $this->separateKey($key);

		$data = unserialize(
			$this->db->fetchField(
				"SELECT `value` FROM `$this->dbName` WHERE `ns` = ? AND `key` = ?", $ns, $key
			)
		);

		$data = $data ?: NULL;

		if ($this->useInternalCache) {
			$this->varCache[$ns.$key] = $data;
		}

		return $data;
	}


	/**
	 * @todo $dependencies
	 */
	public function write($key, $data, array $dependencies)
	{
		list($ns, $key) = $this->separateKey($key);

		$this->db->beginTransaction();

		$exists = $this->db->fetchField(
			"SELECT 1 FROM `$this->dbName` WHERE `ns` = ? AND `key` = ?", $ns, $key
		);

		if ($exists) {
			$this->db->exec(
				"UPDATE `$this->dbName` SET `value` = ?", serialize($data), "
				WHERE `ns` = ? AND `key` = ?", $ns, $key
			);

		} else $this->db->exec(
			"INSERT INTO `$this->dbName` SET ?", [
				'ns' => $ns,
				'key' => $key,
				'value' => serialize($data),
			]
		);

		if ($this->useInternalCache) {
			$this->varCache[$ns.$key] = $data;
		}

		$this->db->commit();
	}


	public function remove($key)
	{
		list($ns, $key) = $this->separateKey($key);

		$this->db->exec(
			"DELETE FROM `$this->dbName`
			WHERE `ns` = ? AND `key` = ?", $ns, $key, "
			LIMIT 1"
		);

		unset($this->varCache[$ns.$key]);
	}


	/**
	 * @todo $conditions
	 */
	public function clean(array $conditions)
	{
		$this->db->exec(
			"DELETE FROM `$this->dbName` WHERE 1"
		);

		$this->varCache = [];
	}


	public function lock($key)
	{
	}


	/**
	 * @param generated key
	 * @return array namespace, key
	 */
	private function separateKey($key)
	{
		return [
			substr($key, 0, -32),
			substr($key, -32)
		];
	}
}
