<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/config/database.php';
require_once __DIR__ . '/src/models/Transaction.php';
require_once __DIR__ . '/src/controllers/application/IndexController.php';

$app = new \App\Controllers\Application\IndexController();

// 3. Chạy hàm index để xử lý logic và hiện giao diện
$app->index();