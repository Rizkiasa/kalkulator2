<?php
class Calculator {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method untuk melakukan perhitungan
    public function calculate($expression) {
        preg_match('/(\d+)\s*([+\-*\/])\s*(\d+)/', $expression, $matches);
        if (!empty($matches)) {
            $operand1 = (int)$matches[1];
            $operator = $matches[2];
            $operand2 = (int)$matches[3];

            switch ($operator) {
                case '+':
                    $result = $operand1 + $operand2;
                    break;
                case '-':
                    $result = $operand1 - $operand2;
                    break;
                case '*':
                    $result = $operand1 * $operand2;
                    break;
                case '/':
                    if ($operand2 != 0) {
                        $result = $operand1 / $operand2;
                    } else {
                        $result = "Error: Pembagian oleh nol";
                    }
                    break;
                default:
                    $result = "Error: Operator tidak valid";
                    break;
            }
        } else {
            $result = "Error: Ekspresi tidak valid";
        }

        return $result;
    }

    // Method untuk menyimpan riwayat perhitungan
    public function saveCalculation($expression, $result) {
        $sql = "INSERT INTO calculations (expression, result) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $expression, $result);
        $stmt->execute();
        $stmt->close();
    }

    // Method untuk mengambil riwayat perhitungan
    public function getHistory() {
        $sql = "SELECT * FROM calculations ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $history = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }
        return $history;
    }
}
?>
