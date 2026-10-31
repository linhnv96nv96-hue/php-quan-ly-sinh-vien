
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "connect.php";

// Nhận ID từ form POST
$id = (int)($_POST["id"] ?? 0);

if ($id <= 0) {
    die("ID sinh viên không hợp lệ!");
}

// Kiểm tra sinh viên tồn tại
$sql = "SELECT id FROM sinh_vien WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id]);

$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("Không tìm thấy sinh viên có ID: " . $id);
}

// Thực hiện xóa
$sql = "DELETE FROM sinh_vien WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id]);

// Quay về trang danh sách
header("Location: index.php");
exit;
?>
