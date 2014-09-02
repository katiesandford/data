<?php

namespace Graze;

use Graze\ReportingAccount;

/**
 * Account class
 */
class ReportingAccount
{
    private $database;
    private $accountId;
    private $firstFullPriceBox;
    private $conversionDate;
    private $numberOfBoxesSent;
    private $numberOfFullPriceBoxesSent;
    private $firstChurnDate;
    private $totalRevenue;
    private $promotionCode;
    private $schedulesTried;


    public function __construct($accountId)
    {    
        $reportingDb = Database::getConnection('reporting');
        $this->database = $reportingDb;
        $this->accountId = $accountId;
        $account = new Account($this->accountId);
        $this->firstFullPriceBox = $account->getFirstBoxId();
        $this->conversionDate = $firstFullPriceBox;
        $this->numberOfBoxesSent = $account->getNumberOfBoxesSent();
        $this->numberOfFullPriceBoxesSent = $account->getNumberOfBoxesSent();
        $this->firstChurnDate = $account->getFirstChurnDate();
        $this->totalRevenue = $account->getTotalRevenue();
        $this->schedulesTried = $account->getSchedulesTried();
        $userEnteredPromotionCode = $account->getPromotionCode();
        //$thirdPartySuppliedPromotionCode = $thirdPartySignUpData[$account->getEmailAddress()];
        
        //if ($userEnteredPromotionCode AND $thirdPartySuppliedPromotionCode){
        //    throw new Exception('Account with id '.strval($accountId).'should not have singed up with a promotion channel if one has been supplied by a third party');
        //}

        $this->promotionChannel = $userEnteredPromotionCode //?: $thirdPartySuppliedPromotionCode ?: "direct";
    }

    public function insertRecordIntoDataBaseTable(){
        $sqlInsert = 'INSERT INTO reporing_account (account_id, conversion_date, boxes_sent, full_price_boxes_sent, first_churn_date, total_revenue, promotion_channel, schedules_tried) VALUES (?,?,?,?,?,?)';
        $this->database->queryPrepared($sqlInsert, array($this->accountId, $this->conversionDate, $this->numberOfBoxesSent, $this->numberOfFullPriceBoxesSent, $this->firstChurnDate, $this->totalRevenue, $this->promotionChannel, $thid->schedulesTried));
        print "Inserted row for account id ".strval($accountId)." into table reporting.reporting_account\n";
    }

}
