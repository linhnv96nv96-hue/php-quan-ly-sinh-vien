
<?php
require_once "admin.php";
require_once "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quan_ly_lop.php");
    exit;
}

$id = $_POST["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID lớp không hợp lệ!");
}

// Kiểm tra lớp còn sinh viên hay không
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM sinh_vien WHERE lop_id = ?"
);
$stmt->execute([$id]);

$soSinhVien = (int) $stmt->fetchColumn();

if ($soSinhVien > 0) {
    die("Không thể xóa lớp vì vẫn còn sinh viên thuộc lớp này!");
}

// Xóa lớp
$stmt = $conn->prepare("DELETE FROM lop_hoc WHERE id = ?");
$stmt->execute([$id]);

header("Location: quan_ly_lop.php");
exit;
