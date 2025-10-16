<?php
namespace App;

use mysqli;

final class Database
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = new mysqli(
            Config::DB_HOST,
            Config::DB_USER,
            Config::DB_PASS,
            Config::DB_NAME,
            Config::DB_PORT
        );

        if ($this->conn->connect_error) {
            throw new \RuntimeException('DB connection error: ' . $this->conn->connect_error);
        }

        // enforce utf8mb4
        $this->conn->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
