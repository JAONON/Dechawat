<?php
$host = "mysql";
$username = "root";
$password = "root";
$dbname = "dechawat";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection error: ' . $e->getMessage());
}

class Database {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getSQL($query, $params = []) {
        foreach ($params as $key => $value) {
            $query = str_replace(":$key", $this->conn->quote($value), $query);
        }
        return $query;
    }
}

$db = new Database($conn);