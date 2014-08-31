<?php

namespace Graze;

/**
 * Schedule Type class
 */
class PromotionCode
{
    private $database;
    private $promotionCodeId;
    private $code;
    private $channel;

    public function __construct($promotionCodeId)
    {
        $this->promotionCodeId = $promotionCodeId;
        $this->database = Database::getConnection();
        $rows = $this->database->fetchAllPrepared("
            SELECT id, code, channel
            FROM schedule_type
            WHERE id = ?",
            array($promotionCodeId));

        if ($rows) {
            $row = array_pop($row);
            $this->code = $row['code'];
            $this->channel = $row['channel'];
        }
    }

    /**
     * get a promotion code
     *
     * @return  String
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * get a promotion code's channel
     *
     * @return  String
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
