NetteDatabase Storage
=====================

Install
-------

### #1

Download [`src/NetteDatabaseStorage.php`](src/NetteDatabaseStorage.php).

### #2

Create cache table in your database (see [`cache.sql`](cache.sql), you can change table name).

### #3

Instantiate `Nette\Caching\Cache`:

```php
$context = new \Nette\Database\Context(...);
$storage = new \Nette\Caching\Storages\DatabaseStorage($context, 'cache_table_name');
$cache = new \Nette\Caching\Cache($storage);
```

### #4

Use it.
