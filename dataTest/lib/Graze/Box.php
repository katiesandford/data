<?php

namespace Graze;

/**
 * Box class
 */
class Box
{
    private $database;
    private $boxId;
    private $accountId;
    private $deliveryDate;
    private $scheduleId;
    private $price;
    private $discount;
    private $dispatchDate = null;

    public function __construct($boxId)
    {
        $this->boxId = $boxId;
        $this->database = Database::getConnection();
        $rows = $this->database->fetchAllPrepared("
            SELECT account_id, delivery_date, schedule_id, price, discount
            FROM box
            WHERE id = ?
        ", array($boxId));

        if ($rows) {
            $row = array_pop($rows);
            $this->accountId = $row['account_id'];
            $this->deliveryDate = $row['delivery_date'];
            $this->scheduleId = $row['schedule_id'];
            $this->price = $row['price'];
            $this->discount = $row['discount'];
        }
    }

    /**
     * Returns the Dispatch date of a given box
     *
     * @return DateTime
     */
    public function getDispatchDate()
    {
        if (!$this->dispatchDate) {
            $deliveryDate = new \DateTime($this->deliveryDate);
            $deliveryDateDow = $deliveryDate->format('l');

            if ($deliveryDateDow == 'Saturday') {
                $dispatchDate = $deliveryDate->modify("-3 day");
            }
            else {
                $dispatchDate = $deliveryDate->modify("-2 day");
            }
            return $dispatchDate;
        }
        return $this->dispatchDate;
    }
}
