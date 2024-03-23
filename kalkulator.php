<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "calculator_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Calculator {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function calculate($expression) {
        $result = eval("return $expression;");
        return $result;
    }

    public function saveCalculation($expression, $result) {
        $sql = "INSERT INTO calculations (expression, result) VALUES ('$expression', '$result')";
        $this->conn->query($sql);
    }

    public function getHistory() {
        $sql = "SELECT * FROM calculations";
        $result = $this->conn->query($sql);
        $history = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }
        return $history;
    }

    public function clearHistory() {
        $sql = "TRUNCATE TABLE calculations";
        $this->conn->query($sql);
    }
}

?>