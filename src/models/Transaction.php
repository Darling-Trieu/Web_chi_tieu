<?php
namespace App\Models;

class Transaction {
    public static function save($data) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['transactions'][] = $data;
    }

    public static function getAll() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['transactions'] ?? [];
    }

    public static function setBudget($amount) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['budget'] = (float)$amount;
    }

    public static function getBudget() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['budget'] ?? 0;
    }

    public static function getBalance() {
        $all = self::getAll();
        $total = 0;
        foreach ($all as $t) {
            $total += ($t['type'] === 'Thu') ? $t['amount'] : -$t['amount'];
        }
        return $total;
    }
}