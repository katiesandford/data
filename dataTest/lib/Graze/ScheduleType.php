<?php

namespace Graze;

/**
 * Schedule Type class
 */
class ScheduleType
{
    private $database;
    private $scheduleTypeId;
    private $scheduleTypeName;

    public function __construct($scheduleTypeId)
    {
        $this->scheduleTypeId = $scheduleTypeId;
        $this->database = Database::getConnection();
        $this->scheduleTypeName = $this->database->fetchColumnPrepared("SELECT name FROM schedule_type WHERE id = ?", array($scheduleTypeId));
    }

    /**
     * get a schedule type's name
     *
     * @return  String
     */
    public function getName()
    {
        return $this->scheduleTypeName;
    }
}
