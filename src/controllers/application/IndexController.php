<?php
namespace App\Controllers\Application;
use App\Models\Transaction;

class IndexController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_GET['action']) && $_GET['action'] === 'export') {
            $this->exportExcel();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['set_budget'])) {
                Transaction::setBudget($_POST['budget_amount']);
            } else {
                $data = [
                    'amount'   => (float)$_POST['amount'],
                    'type'     => $_POST['type'],
                    'category' => ($_POST['type'] === 'Thu') ? 'Thu nhập' : $_POST['category'],
                    'date'     => $_POST['date'],
                    'note'     => $_POST['note']
                ];
                Transaction::save($data);
            }
            header("Location: index.php?page=" . ($_GET['page'] ?? 'home'));
            exit;
        }

        $transactions = Transaction::getAll();
        $balance = Transaction::getBalance();
        $budget = Transaction::getBudget();
        $page = $_GET['page'] ?? 'home';

        require_once $_SERVER['DOCUMENT_ROOT'] . '/src/views/pages/application/Appindex.php';
    }

    private function exportExcel() {
        $transactions = Transaction::getAll();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=GiaoDich_'.date('Y-m-d').'.csv');
        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM cho tiếng Việt
        fputcsv($output, ['Ngày', 'Số Tiền', 'Loại', 'Danh Mục', 'Ghi Chú']);
        foreach ($transactions as $t) {
            fputcsv($output, [$t['date'], $t['amount'], $t['type'], $t['category'], $t['note']]);
        }
        fclose($output);
        exit;
    }
}