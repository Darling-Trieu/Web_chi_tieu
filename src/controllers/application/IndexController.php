<?php
namespace App\Controllers\Application;

use App\Models\Transaction;

class IndexController {

    public function index() {

        // start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // export CSV
        if (isset($_GET['action']) && $_GET['action'] === 'export') {
            $this->exportExcel();
        }

        // xử lý POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['clear_all'])) {
                Transaction::clearAll();
            }

            if (isset($_POST['set_budget'])) {
                Transaction::setBudget((float)$_POST['budget_amount']);
            } 
            else {
                $data = [
                    'amount'   => (float)$_POST['amount'],
                    'type'     => $_POST['type'],
                    'category' => ($_POST['type'] === 'Thu') 
                                    ? 'Thu nhập' 
                                    : $_POST['category'],
                    'date'     => $_POST['date'],
                    'note'     => $_POST['note']
                ];

                Transaction::save($data);
            }

            header("Location: index.php?page=" . ($_GET['page'] ?? 'home'));
            exit;
        }

        // 🔥 DATA CHÍNH
        $transactions = Transaction::getAll();
        $balance      = Transaction::getBalance();
        $budget       = Transaction::getBudget();

        // 🔥 THÊM DÒNG NÀY (QUAN TRỌNG NHẤT)
        $monthlyStats = Transaction::getMonthlyStats();

        // page
        $page = $_GET['page'] ?? 'home';

        // render view
        require $_SERVER['DOCUMENT_ROOT'] . '/src/views/pages/application/AppIndex.php';
    }

    private function exportExcel() {

        $transactions = Transaction::getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=GiaoDich_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // BOM UTF-8
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, ['Ngày', 'Số Tiền', 'Loại', 'Danh Mục', 'Ghi Chú']);

        foreach ($transactions as $t) {
            fputcsv($output, [
                $t['date'],
                $t['amount'],
                $t['type'],
                $t['category'],
                $t['note']
            ]);
        }

        fclose($output);
        exit;
    }
}