NetteDatabase Storage
=====================

Install
-------

### #1

Create cache table in your database (see `cache.sql`, you can change table name).

### #2

Instantiate `Nette\Caching\Cache`:

```php
$context = new \Nette\Database\Context(...);
$storage = new \Nette\Caching\Storages\DatabaseStorage($context, 'cache_table_name');
$cache = new \Nette\Caching\Cache($storage);
```

### #3

Use it.
