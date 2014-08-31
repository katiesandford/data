<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;

//example of use of the getSchedulesTried method
$account = new Account(3525886);
$schedulesTried = $account->getSchedulesTried();
$numSchedulesTried = count($schedulesTried);
print "Account ".$account->getId()." has tried $numSchedulesTried schedules \n";

//example of querying the database directly
$database = Database::getConnection();
$code = "TASTY8";
$codeChannel = $database->fetchColumnPrepared("SELECT channel FROM promotion_code WHERE code = ?", array($code));
print "Code $code belongs to channel '$codeChannel'\n";

// Example of creating a new table;
$reportingDb = Database::getConnection('reporting');
$sql = "CREATE TABLE example (id INT(10))";
$reportingDb->queryPrepared($sql);
print "Created table reporting.example\n";

// Example of droppping a table
$sql = "DROP TABLE example";
$reportingDb->queryPrepared($sql);
print "Dropped table reporting.example\n";
