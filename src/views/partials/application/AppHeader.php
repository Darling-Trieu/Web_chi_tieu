<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/function.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chi Tiêu</title>
    <link rel="stylesheet" href="/public/css/globals.css?<?php echo time(); ?>">
</head>
<body>

<header class="app-header">
    <h1>Quản lý Chi Tiêu</h1>
    <nav>
        <a href="/application">Trang chủ</a>
        <a href="/application/create">Thêm chi tiêu</a>
        <a href="/application/report">Báo cáo</a>
    </nav>
</header>