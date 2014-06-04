NetteDatabase Storage
=====================

Install
-------

### #1

Download [`src/NetteDatabaseStorage.php`](src/NetteDatabaseStorage.php).

### #2

Create cache table in your database (see [`mysql.cache.sql`](assets/mysql.cache.sql) or
[`postgres.cache.sql`](assets/postgres.cache.sql), you can adapt script for another database server (not tested);
you can change table name).

### #3

Instantiate `Nette\Caching\Cache`:

```php
$context = new \Nette\Database\Context(...);
$storage = new \Nette\Caching\Storages\DatabaseStorage($context, 'cache_table_name');
$cache = new \Nette\Caching\Cache($storage);
```

### #4

Use it.
