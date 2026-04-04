<?php
// 1. Nạp các file logic
require_once __DIR__ . '/src/models/Transaction.php';
require_once __DIR__ . '/src/controllers/application/IndexController.php';

// 2. Khởi tạo Controller
// Lưu ý: Sử dụng đúng Namespace nếu bạn đã đặt trong Controller
$app = new \App\Controllers\Application\IndexController();

// 3. Chạy hàm index để xử lý logic và hiện giao diện
$app->index();