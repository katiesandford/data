<?php

namespace Graze;

use Graze\Database\Connection;

/**
 * Class for interacting with a connection to a given database
 */
class Database
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private static $connections;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public static function getConnection($schema = 'live')
    {
        if (!self::$connections) {
            self::$connections = array();
        }

        if (! array_key_exists($schema, self::$connections)) {
            self::$connections[$schema] = new Connection($schema);
        }

        return self::$connections[$schema];
    }
}
