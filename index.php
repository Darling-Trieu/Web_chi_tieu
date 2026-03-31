<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chi Tiêu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="balance-bar">
            <span>Số tiền hiện có:</span>
            <strong id="currentBalance">0 ₫</strong>
        </div>
        <div class="form-card">
            <h1 class="title">Thêm Giao Dịch Chi Tiêu</h1>
            <form id="expenseForm" method="post" action="">
                <div class="form-group">
                    <label for="amount">Số Tiền</label>
                    <input type="number" id="amount" name="amount" placeholder="Nhập số tiền..." required min="0" step="0.01">
                    <div class="error" id="amountError"></div>
                </div>
                <div class="form-group">
                    <label for="type">Loại Giao Dịch</label>
                    <select id="type" name="type" required>
                        <option value="">Chọn loại</option>
                        <option value="Thu">Thu</option>
                        <option value="Chi">Chi</option>
                    </select>
                    <div class="error" id="typeError"></div>
                </div>
                <div class="form-group">
                    <label for="category">Danh Mục</label>
                    <select id="category" name="category" required>
                        <option value="">Chọn danh mục</option>
                        <option value="Ăn uống">Ăn uống</option>
                        <option value="Di chuyển">Di chuyển</option>
                        <option value="Mua sắm">Mua sắm</option>
                        <option value="Giải trí">Giải trí</option>
                        <option value="Khác">Khác</option>
                    </select>
                    <div class="error" id="categoryError"></div>
                </div>
                <div class="form-group">
                    <label for="date">Ngày Giao Dịch</label>
                    <input type="date" id="date" name="date" required>
                    <div class="error" id="dateError"></div>
                </div>
                <div class="form-group">
                    <label for="note">Ghi Chú (Tùy chọn)</label>
                    <textarea id="note" name="note" rows="3" placeholder="Nhập ghi chú..."></textarea>
                </div>
                <button type="submit" class="btn-submit">Lưu Giao Dịch</button>
            </form>
        </div>
    </div>

    <!-- Link script riêng -->
    <script src="script.js"></script>
</body>
</html>