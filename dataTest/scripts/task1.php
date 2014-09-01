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
	print "Inserted row for account id ".strval($accountId)." into table reporting.reporting_account\n";
}

foreach ($accountIds as $accountId) {
	createReportingAccount($accountId);
}

//Task2
//Directory Iterator loops through items in a directory

function parseThirdPartyFiles (){
	$iterator = new DirectoryIterator('/data');
	$thirdPartySignUpData = array();
	//Iterate though files in the data directory
	foreach ($iterator as $itemInfo) {
		if ($itemInfo->isFile()){
			//Open the file
			$file = fopen($itemInfo->getPathname(), "r") or die("Unable to open file!");
			//Iterate through lines in the file
			while(! feof($file)) {
				$line = fgets($file);
	  			$lineData =  explode(" ", $line);
	  			assert(count($lineData)==3, 'A line in file '.$itemInfo->getFilename().' does not contain excalty 3 items: ' .$line);
	  			if ($thirdPartySignUpData[$lineData[0]]){
	  				die("We have duplicate sign up data has been found!");
	  			}
	  			//$lineData[1] = date
	  			//$lineData[2] = promotion code
	  			$thirdPartySignUpData[$lineData[0]] = array($lineData[1], $lineData[2]);
	  		}
			
		}

	}
	return $thirdPartySignUpData[$lineData[0]];
}
$thirdPartySignUpData = parseThirdPartyFiles();
print_r($thirdPartySignUpData);