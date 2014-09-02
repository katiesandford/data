<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;


$reportingDb = Database::getConnection('reporting');
$liveDb = Database::getConnection('live');

// Get all account ids from the live schema
$sql = 'SELECT id FROM account';
$accountIds = $liveDb->fetchAllPrepared($sql);

/** 
 *Iterate though supplied third party files.
 *Store the email address and promotion code as key value pairs in an accessible array 
 *@return array list dictionary of email key => promotion code
*/
function parseThirdPartyFiles(){
	$iterator = new DirectoryIterator('/home/vagrant/data/dataTest/data');
	$thirdPartySignUpData = array();
	foreach ($iterator as $itemInfo) {
		if ($itemInfo->isFile()){
			$file = fopen($itemInfo->getPathname(), "r") or die("Unable to open file!");         
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
		  		$thirdPartySignUpData[$lineData[0]] = $lineData[1];
		  	}
			
		}

	}
	return $thirdPartySignUpData[$lineData[0]];
}


function createReportingAccount($account){
	$accoundId = $account->getId();
	$firstFullPriceBox = $account->getFirstBoxId();
	$conversionDate = $firstFullPriceBox;
	$numberOfBoxesSent = $account->getNumberOfBoxesSent();
	$numberOfFullPriceBoxesSent = $account->getNumberOfBoxesSent();
	$firstChurnDate = $account->getFirstChurnDate();
	$totalRevenue = $account->getTotalRevenue();
	$userEnteredPromotionCode = $account->getPromotionCode();
	$thirdPartySuppliedPromotionCode = $thirdPartySignUpData[$account->getEmailAddress()];
	$schedulesTried = $account->getSchedulesTried();
	
	if ($userEnteredPromotionCode AND $thirdPartySuppliedPromotionCode){
		throw new Exception('Account with id '.strval($accountId).'should not have singed up with a promotion channel if one has been supplied by a third party');
	}

	$promotionChannel = $userEnteredPromotionCode ?: $thirdPartySuppliedPromotionCode ?: "direct";

	$sqlInsert = 'INSERT INTO reporing_account (account_id, conversion_date, boxes_sent, full_price_boxes_sent, first_churn_date, total_revenue, promotion_channel, schedules_tried) VALUES (?,?,?,?,?,?)';
	$reportingDb->queryPrepared($sqlInsert, array($accountId, $conversionDate, $numberOfBoxesSent, $numberOfFullPriceBoxesSent, $firstChurnDate, $totalRevenue, $promotionChannel, $schedulesTried));
	print "Inserted row for account id ".strval($accountId)." into table reporting.reporting_account\n";
}



$thirdPartySignUpData = parseThirdPartyFiles();
print 'aaaaa';
print_r($thirdPartySignUpData);
//
foreach ($accountIds as $accountId) {
	$account = new Account($accountId);
	createReportingAccount($account);
}
