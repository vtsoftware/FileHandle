#!/usr/bin/php8.0
<?php declare(strict_types=1);

require_once('FileHandle.php');
require_once('File.php');

File::write('testfile.txt', date('c'));

var_dump(File::read('testfile.txt'));
