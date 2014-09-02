<?php

namespace Graze;

use Graze\Account;

/**
 * Account class
 */
class Account
{
    private $database;
    private $accountId;
    private $signupDatetime;
    private $promotionCode;
    private $emailAddress;

    public function __construct($accountId)
    {
        $this->accountId = $accountId;
        $account = new Account($this->accountId);
        $this->database = Database::getConnection();
        $rows = $this->database->fetchAllPrepared("
            SELECT id, signup_datetime, promotion_code, email_address
            FROM account
            WHERE id = ?
        ", array($accountId));

        if ($rows) {
            $data = array_pop($rows);
            $this->accountId = $data['id'];
            $this->signupDatetime = $data['signup_datetime'];
            $this->promotionCode = $data['promotion_code'];
            $this->emailAddress = $data['email_address'];
        }
    }

    /**
     * Get a list of tried schedules
     *
     * @return  ScheduleType[] List of schedule types the account has tried
     */
    public function getSchedulesTried()
    {
        $schedulesTried = array();

        // get customer boxes
        $sql = 'SELECT DISTINCT type FROM schedule WHERE account_id = ?';
        $results = $this->database->fetchAllPrepared($sql, array($this->accountId));

        foreach($results as $result) {
            $scheduleType = new ScheduleType($result['type']);
            $schedulesTried[] = $scheduleType;
        }

        return $schedulesTried;
    }

    public function getId()
    {
        return $this->accountId;
    }

    public function getSignupDatetime()
    {
        return $this->signupDatetime;
    }

    public function getPromotionCode()
    {
        return $this->promotionCode;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    //Functions added by me
    public function getFirstChurnDate()
    {
        $sql = 'SELECT MAX(end_date) FROM schedule WHERE account_id = ?';
        $results = $this->database->fetchAllPrepared($sql, array($this->accountId));
        return array_values($results)[0] ?: NULL;
    }

    public function getNumberOfBoxesSent($price = NULL)
    {
        $sql = 'SELECT COUNT DISTINCT * FROM box WHERE account_id = ?';
        if (!IS_NULL($price)) {
            $sql .= ' AND price = ?'
        }
        $results = $this->database->fetchAllPrepared($sql, array($this->accountId, $price));
        return array_values($results)[0] ?: 0;
    }

    public function getFirstBoxId()
    {
        $sql = 'SELECT TOP 1 id * FROM box WHERE account_id = ? ORDER BY DATE';
        $results = $this->database->fetchAllPrepared($sql, array($this->accountId, $price));
        return array_values($results)[0] ?: NULL;
    }

    public function getTotalRevenue()
    {
        $sql = 'SELECT SUM(price) FROM box WHERE account_id = ?';
        $results = $this->database->fetchAllPrepared($sql, array($this->accountId, $price));
        return array_values($results)[0] ?: 0;
    }
}
