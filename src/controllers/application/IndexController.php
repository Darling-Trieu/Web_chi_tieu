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

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=GiaoDich_" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $tongThu = 0;
        $tongChi = 0;

        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head>';
        echo '<body>';
        echo '<table border="1">';
        echo '<tr><th>Ngày</th><th>Số Tiền</th><th>Loại</th><th>Danh Mục</th><th>Ghi Chú</th></tr>';
        
        foreach ($transactions as $t) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($t['date']) . '</td>';
            echo '<td>' . htmlspecialchars($t['amount']) . '</td>';
            echo '<td>' . htmlspecialchars($t['type']) . '</td>';
            echo '<td>' . htmlspecialchars($t['category']) . '</td>';
            echo '<td>' . htmlspecialchars($t['note']) . '</td>';
            echo '</tr>';

            if ($t['type'] === 'Thu') {
                $tongThu += (float)$t['amount'];
            } else {
                $tongChi += (float)$t['amount'];
            }
        }

        echo '<tr><td colspan="5"></td></tr>';
        echo '<tr><td colspan="4" style="text-align: right;"><b>Tổng Thu:</b></td><td><b>' . $tongThu . '</b></td></tr>';
        echo '<tr><td colspan="4" style="text-align: right;"><b>Tổng Chi:</b></td><td><b>' . $tongChi . '</b></td></tr>';
        
        echo '</table></body></html>';
        exit;
    }
}