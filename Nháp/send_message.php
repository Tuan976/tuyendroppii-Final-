<?php
declare(strict_types=1);

/*
  send_message.php v2.2
  - Lưu liên hệ vào MySQL (tin_nhan)
  - JSON khi AJAX hoặc khi thêm ?debug=1, ngược lại redirect /index.html?status=...
*/

const DB_HOST = 'localhost';
const DB_USER = 'nhtuyr67_user_gui';
const DB_PASS = 'Hoanghuy05';
const DB_NAME = 'nhtuyr67_nhtuyr67_db_tinnhan'; // CHUẨN THEO cPanel

function field(string $k): string { return isset($_POST[$k]) ? trim((string)$_POST[$k]) : ''; }
function isAjax(): bool { return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest'; }
function isDebug(): bool { return isset($_GET['debug']); }
function respond(bool $ok, string $msg, array $extra=[]): void {
  if (isAjax() || isDebug()) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['success'=>$ok,'message'=>$msg]+$extra, JSON_UNESCAPED_UNICODE);
  } else {
    header('Location: /index.html?status=' . ($ok?'success':'error') . '#contact');
  }
  exit;
}
function logErr(string $m): void {
  if (isDebug()) file_put_contents(__DIR__.'/send_message_debug.log', date('c')." $m\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo 'Method Not Allowed'; exit; }

$name    = field('name') ?: field('customerName');
$phone   = field('phone') ?: field('customerPhone');
$email   = field('email') ?: field('customerEmail');
$subject = field('subject') ?: 'Liên hệ từ website';
$message = field('message');

if ($name === '' || $message === '') respond(false, 'Thiếu họ tên hoặc nội dung.');
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) respond(false, 'Email không hợp lệ.');
if ($phone && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) respond(false, 'Số điện thoại không hợp lệ.');
if (mb_strlen($name)>150 || mb_strlen($subject)>150 || mb_strlen($message)>5000) respond(false, 'Trường nhập quá dài.');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_errno) {
  logErr('DB connect error: '.$conn->connect_error);
  respond(false, 'Không kết nối được CSDL.', isDebug()?['detail'=>$conn->connect_error]:[]);
}
$conn->set_charset('utf8mb4');

$createSQL = "CREATE TABLE IF NOT EXISTS tin_nhan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nguoi_gui VARCHAR(150),
  sdt VARCHAR(40),
  email VARCHAR(150),
  chu_de VARCHAR(150),
  noi_dung TEXT,
  ip VARCHAR(45),
  user_agent VARCHAR(255),
  ngay_gio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$conn->query($createSQL)) {
  logErr('Create table error: '.$conn->error);
  respond(false, 'Không thể tạo bảng.', isDebug()?['detail'=>$conn->error]:[]);
}

$stmt = $conn->prepare("INSERT INTO tin_nhan (nguoi_gui, sdt, email, chu_de, noi_dung, ip, user_agent) VALUES (?,?,?,?,?,?,?)");
if (!$stmt) {
  logErr('Prepare error: '.$conn->error);
  respond(false, 'Lỗi hệ thống (prepare).', isDebug()?['detail'=>$conn->error]:[]);
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
$stmt->bind_param('sssssss', $name, $phone, $email, $subject, $message, $ip, $ua);

if (!$stmt->execute()) {
  $err = $stmt->error;
  logErr('Execute error: '.$err);
  $stmt->close(); $conn->close();
  respond(false, 'Lưu thất bại.', isDebug()?['detail'=>$err]:[]);
}

$stmt->close(); $conn->close();
respond(true, 'Đã lưu tin nhắn.');
?>