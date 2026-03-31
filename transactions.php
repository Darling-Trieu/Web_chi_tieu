<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'student_expense';  
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // bật exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];


try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Không thể kết nối database']);
    exit;
}

// --- Nhận dữ liệu từ POST ---
$input = json_decode(file_get_contents('php://input'), true);

$user_id    = $input['user_id'] ?? null;
$amount     = $input['amount'] ?? null;
$category_id= $input['category_id'] ?? null;
$date       = $input['date'] ?? null;

// --- Validate dữ liệu ---
$errors = [];

if (!is_numeric($amount) || $amount <= 0) $errors['amount'] = "Số tiền phải lớn hơn 0";
if (!is_numeric($user_id) || $user_id <= 0) $errors['user_id'] = "User không hợp lệ";
if (!is_numeric($category_id) || $category_id <= 0) $errors['category_id'] = "Danh mục không hợp lệ";
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || strtotime($date) > time())
    $errors['date'] = "Ngày giao dịch không hợp lệ";

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success'=>false, 'errors'=>$errors]);
    exit;
}

// --- Lưu giao dịch vào DB ---
try {
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, category_id, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $amount, $category_id, $date]);

    $id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $id,
            'user_id' => $user_id,
            'amount' => $amount,
            'category_id' => $category_id,
            'date' => $date
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>'Lỗi server, không thể lưu giao dịch']);
}
?>