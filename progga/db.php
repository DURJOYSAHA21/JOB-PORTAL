<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Database
{
    private $hostname = "localhost";
    private $db_username = "root";
    private $db_password = "";
    private $db_name = "job_portal";

    public function connect()
    {
        $conn = new mysqli(
            $this->hostname,
            $this->db_username,
            $this->db_password,
            $this->db_name
        );

        if ($conn->connect_error) {
            die("DB connection failed: " . $conn->connect_error);
        }

        $conn->set_charset("utf8mb4");
        return $conn;
    }
}
