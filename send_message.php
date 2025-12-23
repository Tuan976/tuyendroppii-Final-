<xaiArtifact artifact_id="2cb3ed2f-9531-4ac8-b18f-3805a1c12b57" artifact_version_id="830ef8a9-c719-41a6-92a8-36319dd4d99a" title="send_message.php" contentType="application/x-php">
```php
<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Location: index.php?status=error');
        exit();
    }

    // Lấy và làm sạch dữ liệu
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
    $subject = isset($_POST['subject']) ? trim(htmlspecialchars($_POST['subject'])) : 'Không có tiêu đề';
    $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';

    // Xác thực dữ liệu
    $errors = [];
    if (empty($name)) {
        $errors[] = 'Vui lòng nhập họ và tên.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Vui lòng nhập email hợp lệ.';
    }
    if (empty($message)) {
        $errors[] = 'Vui lòng nhập nội dung tin nhắn.';
    }

    if (empty($errors)) {
        // Định dạng dữ liệu để lưu vào file
        $data = "Thời gian: " . date('Y-m-d H:i:s') . "\n";
        $data .= "Họ và tên: $name\n";
        $data .= "Email: $email\n";
        $data .= "Tiêu đề: $subject\n";
        $data .= "Nội dung: $message\n";
        $data .= "----------------------------------------\n";

        // Lưu vào file contact_data.txt
        $file = 'contact_data.txt';
        if (file_put_contents($file, $data, FILE_APPEND | LOCK_EX) !== false) {
            // Xóa CSRF token sau khi sử dụng
            unset($_SESSION['csrf_token']);
            header('Location: index.php?status=success');
            exit();
        } else {
            header('Location: index.php?status=error');
            exit();
        }
    } else {
        header('Location: index.php?status=error');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
```