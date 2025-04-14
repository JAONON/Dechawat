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
    private $lastQuery;
    private $lastParams;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function fetchOne($query, $params = []) {
        $this->lastQuery = $query;
        $this->lastParams = $params;

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($query, $params = []) {
        $this->lastQuery = $query;
        $this->lastParams = $params;

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSQL() {
        $query = $this->lastQuery;
        $params = $this->lastParams;

        foreach ($params as $key => $value) {
            $escapedValue = is_null($value) ? 'NULL' : $this->conn->quote($value);
            $query = str_replace(":$key", $escapedValue, $query);
        }
        return $query;
    }
}
