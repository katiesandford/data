<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;

// Get all account ids from the live schema
$reportingDb = Database::getConnection('reporting');
$liveDb = Database::getConnection('live');
$sql = 'SELECT id FROM account'

$accountIds = $liveDb->fetchAllPrepared($sql);

function createReportingAccount($accountId){

	$account = new Account($accountId);

	//Get conveversion date
	$firstFullPriceBox = $account->getFirstBoxId(300);
	$conversionDate = $firstFullPriceBox

	//Get number of boxes sent
	$numberOfBoxesSent = $account->getNumberOfBoxesSent();

	//Get number of full price boxes sent
	$numberOfFullPriceBoxesSent = $account->getNumberOfBoxesSent(300)l

	//Get first churn date
	$firstChurnDate = $account->getFirstChurnDate();

	//Get total revenue
	$totalRevenue = $account->getTotalRevenue();

	$sqlInsert = 'INSERT INTO reporing_account (account_id, conversion_date, boxes_sent, full_price_boxes_sent, first_churn_date, total_revenue) VALUES (?,?,?,?,?,?)'
	reportingDb->queryPrepared($sqlInsert, array($accountId, $conversionDate, $numberOfBoxesSent, $numberOfFullPriceBoxesSent, $firstChurnDate, $totalRevenue));
	print "Inserted row for account id ".$accountId." into table reporting.reporting_account\n";
}

foreach ($accountIds as $accountId) {
	createReportingAccount($accountId);
}