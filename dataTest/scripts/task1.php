<?php

// Include Vendor Packages from Composer
require 'vendor/autoload.php';

use Graze\Account;
use Graze\Database;
use Graze\ReportingAccount;

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

$thirdPartySignUpData = parseThirdPartyFiles();

print_r($thirdPartySignUpData);
//
foreach ($accountIds as $accountId) {
	$reportingAccount =  new ReportingAccount($accountId);
	$reportingAccount->insertRecordIntoDataBaseTable();
}
