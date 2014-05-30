<?php
/**
 * @todo tests interfere together, so they does not pass while executed paralleled (just rarely)
 * I don't know how to fix it now...
 */

require 'bootstrap.php';

use Tester\Assert;

// cleanup after previously executed test
$context->table($db['table'])->where('key', ['mafioso', 'weapon'])->delete();

// write some data
$storage->write('mafioso', 'Vincent Vega', []);
$storage->write('weapon', 'gun', []);

// clean
$storage->clean([\Nette\Caching\Cache::ALL => TRUE]);
Assert::null($storage->read('mafioso'));
Assert::null($storage->read('weapon'));
