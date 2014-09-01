<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;

// Get all account ids from the live schema
$reportingDb = Database::getConnection('reporting');
$liveDb = Database::getConnection('live');
$sql = 'SELECT id FROM account';

$accountIds = $liveDb->fetchAllPrepared($sql);


function parseThirdPartyFiles (){
	$iterator = new DirectoryIterator('/home/vagrant/data/dataTest/data');
	$thirdPartySignUpData = array();
	//Iterate though files in the data directory
	foreach ($iterator as $itemInfo) {
		if ($itemInfo->isFile()){
			//Open the file
			$file = fopen($itemInfo->getPathname(), "r") or die("Unable to open file!");
			//Iterate through lines in the file
                        
			while(! feof($file)) {
				$line = fgets($file);
		  		$lineData =  explode("\t", $line);
		  		if (!count($lineData)==3){
		  			continue;
		  		}
		  		if (array_key_exists($lineData[0], $thirdPartySignUpData)){
                                        continue;
		  			throw new Exception("File *".$itemInfo->getFilename().") contains duplicate data: ".$lineData);
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
print 'aaaaa';
print_r($thirdPartySignUpData);
print 'bbbb';
function createReportingAccount($accountId){

	$account = new Account($accountId);

	//Get conveversion date
	$firstFullPriceBox = $account->getFirstBoxId();
	$conversionDate = $firstFullPriceBox;
	$numberOfBoxesSent = $account->getNumberOfBoxesSent();
	$numberOfFullPriceBoxesSent = $account->getNumberOfBoxesSent();
	$firstChurnDate = $account->getFirstChurnDate();
	$totalRevenue = $account->getTotalRevenue();
	$userEnteredPromotionCode = $account->getPromotionCode();
	$thirdPartySuppliedPromotionCode = $thirdPartySignUpData[$account->getEmailAddress()];
	
	if ($userEnteredPromotionCode AND $thirdPartySuppliedPromotionCode){
		throw new Exception('Account with id '.strval($accountId).'should not have singed up with a promotion channel if one has been supplied by a third party');
	}

	$promotionChannel = $userEnteredPromotionCode ?: $thirdPartySuppliedPromotionCode ?: "direct";

	$sqlInsert = 'INSERT INTO reporing_account (account_id, conversion_date, boxes_sent, full_price_boxes_sent, first_churn_date, total_revenue, promotion_channel) VALUES (?,?,?,?,?,?)';
	$reportingDb->queryPrepared($sqlInsert, array($accountId, $conversionDate, $numberOfBoxesSent, $numberOfFullPriceBoxesSent, $firstChurnDate, $totalRevenue, $promotionChannel));
	print "Inserted row for account id ".strval($accountId)." into table reporting.reporting_account\n";
}

foreach ($accountIds as $accountId) {
	createReportingAccount($accountId);
}
