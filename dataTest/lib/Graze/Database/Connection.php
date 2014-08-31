<?php

namespace Graze\Database;

use Doctrine\DBAL\DriverManager;

/**
 * Class for interacting with a connection to a given database
 */
class Connection
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct($schema = 'live')
    {
        $connection = DriverManager::getConnection(array(
            'dbname' => $schema,
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'driver' => 'pdo_mysql',
        ));

        $this->connection = $connection;
    }

    /**
     * Performs a Statement::fetchAll() with bound values
     *
     * @param String $sql
     * @param Array $boundValues
     * @return array An array containing all of the rows in the result set.
     */
    public function fetchAllPrepared($sql, $boundValues = array())
    {
        $i = 1;
        $stmt = $this->connection->prepare($sql);
        foreach($boundValues as $boundValue) {
            $stmt->bindValue($i++, $boundValue);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    /**
     * Performs a Statement::fetchColumn() with bound values
     *
     * @param String $sql
     * @param Array $boundValues
     * @return  mixed A single column from the next row of a result set or FALSE if there are no rows.
     */
    public function fetchColumnPrepared($sql, $boundValues = array())
    {
        $i = 1;
        $stmt = $this->connection->prepare($sql);
        foreach($boundValues as $boundValue) {
            $stmt->bindValue($i++, $boundValue);
        }
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return $result;
    }

    /**
     * Performs a Statement::execute() with bound values
     *
     * @param String $sql
     * @param Array $boundValues
     * @return Boolean
     */
    public function queryPrepared($sql, $boundValues = array())
    {
        $i = 1;
        $stmt = $this->connection->prepare($sql);
        foreach($boundValues as $boundValue) {
            $stmt->bindValue($i++, $boundValue);
        }
        $result = $stmt->execute();

        return $result;
    }
}
