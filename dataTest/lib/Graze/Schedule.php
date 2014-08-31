<?php

namespace Graze;

/**
 * Schedule class
 */
class Schedule
{
    private $database;
    private $scheduleId;
    private $accountId;
    private $typeId;
    private $startDate;
    private $endDate;

    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->database = Database::getConnection();
        $rows = $this->database->fetchAllPrepared("
            SELECT account_id, type, start_date, end_date
            FROM schedule
            WHERE id = ?
        ", array($scheduleId));

        if ($rows) {
            $data = array_pop($rows);
            $this->accountId = $data['account_id'];
            $this->typeId = $data['type'];
            $this->startDate = $data['start_date'];
            $this->endDate = $data['end_date'];
        }
    }

    public function getId()
    {
        return $this->scheduleId;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

}
