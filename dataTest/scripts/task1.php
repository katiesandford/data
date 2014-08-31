<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;
..somehow define variables....task1

// Example of creating a new table;
$reportingDb = Database::getConnection('reporting');
$sql = file_get_contents('sql\task1.sql')
$reportingDb->queryPrepared($sql);
print "Created table reporting.reposrting_account \n";
