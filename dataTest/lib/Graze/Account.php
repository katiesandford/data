<?php

namespace Graze;

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
}
