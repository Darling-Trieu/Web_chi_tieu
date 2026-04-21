<?php
namespace App\Models;

use mysqli;

class Transaction {

    private static function connect() {
        return new mysqli(
    SERVERNAME,
    USERNAME,
    PASSWORD,
    DATABASE
);
    }

    // Lưu giao dịch
    public static function save($data) {
        $conn = self::connect();

        $stmt = $conn->prepare("
            INSERT INTO transactions (amount, type, category, date, note)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "dssss",
            $data['amount'],
            $data['type'],
            $data['category'],
            $data['date'],
            $data['note']
        );

        return $stmt->execute();
    }

    // Lấy tất cả giao dịch
    public static function getAll() {
        $conn = self::connect();

        $result = $conn->query("
            SELECT * FROM transactions WHERE date <= CURDATE() ORDER BY date DESC
        ");

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // Tính số dư
    public static function getBalance() {
        $conn = self::connect();

        $result = $conn->query("
            SELECT 
                SUM(CASE WHEN type='Thu' THEN amount ELSE 0 END) -
                SUM(CASE WHEN type='Chi' THEN amount ELSE 0 END)
            AS balance
            FROM transactions
            WHERE date <= CURDATE()
        ");

        return $result->fetch_assoc()['balance'] ?? 0;
    }

    // Lấy ngân sách
    public static function getBudget() {
        $conn = self::connect();

        $result = $conn->query("SELECT amount FROM budget WHERE id = 1");

        return $result->fetch_assoc()['amount'] ?? 0;
    }

    // Cập nhật ngân sách
    public static function setBudget($amount) {
        $conn = self::connect();

        $stmt = $conn->prepare("
            UPDATE budget SET amount = ? WHERE id = 1
        ");

        $stmt->bind_param("d", $amount);
        return $stmt->execute();
    }
    public static function clearAll() {
    $conn = self::connect();
    return $conn->query("TRUNCATE TABLE transactions");
    }
    public static function getMonthlyStats() {
    $conn = self::connect();

    $result = $conn->query("
        SELECT 
            MONTH(date) as month,
            SUM(CASE WHEN type='Thu' THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type='Chi' THEN amount ELSE 0 END) as expense
        FROM transactions
        GROUP BY MONTH(date)
    ");
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $data[$i] = [
            'income' => 0,
            'expense' => 0
        ];
    }

    while ($row = $result->fetch_assoc()) {
        $month = (int)$row['month'];
        $data[$month] = [
            'income' => (float)$row['income'],
            'expense' => (float)$row['expense']
        ];
    }
    return array_values($data);
    }
}