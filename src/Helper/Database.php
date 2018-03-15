<?php
namespace TextOnImage\Helper;

use \PDO;
use \PDOException;
use Exception;

/**
 * Class Database
 * @package Flexi\Database
 */
class Database
{
    /**
     * @var PDO The database connection.
     */
    public static $connection;

    /**
     * Get the current connection.
     *
     * @return PDO
     */
    public static function connection()
    {
        return static::$connection;
    }

    /**
     * Initializes the database connection.
     *
     * @return void
     */
    public static function initialize()
    {
        // Connect to the database.
        static::$connection = static::connect();
    }

    /**
     * Finalize the database connection.
     *
     * @return void
     */
    public static function finalize()
    {
        // Close connection.
        static::$connection = null;
    }

    /**
     * Connect to the database.
     *
     * @return null|PDO
     * @throws Exception
     */
    private static function connect()
    {
        // Setup connection settings.
        $config = require_once __DIR__ . '/../../Config/database.php';

        $driver     = $config['driver'];
        $host       = $config['host'];
        $username   = $config['username'];
        $password   = $config['password'];
        $name       = $config['db_name'];
        $charset    = $config['charset'];

        $dsn        = sprintf('%s:host=%s;dbname=%s;charset=%s', $driver, $host, $name, $charset);
        $options    = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        // Don't attempt a connection if we have no database username, name
        // or driver.
        if ($driver === '' || $username === '' || $name === '') {
            return null;
        }

        // Attempt to connect to the database.
        try {
            $connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $error) {
            throw new Exception($error->getMessage());
        }

        // Return connection if successful.
        return $connection ?? null;
    }

    /**
     * Gets the last inserted record ID.
     *
     * @return int
     */
    public static function insertId(): int
    {
        return (int) static::$connection->lastInsertId();
    }
}
