<?php
require_once "admin.php";
require_once "connect.php";

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID lớp không hợp lệ!");
}

// Lấy thông tin lớp
$stmt = $conn->prepare("SELECT * FROM lop_hoc WHERE id = ?");
$stmt->execute([$id]);
$lop = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lop) {
    die("Không tìm thấy lớp học!");
}

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenLop = trim($_POST["ten_lop"] ?? "");

    if ($tenLop === "") {
        $thongBao = "Vui lòng nhập tên lớp!";
    } else {
        $stmt = $conn->prepare(
            "UPDATE lop_hoc SET ten_lop = ? WHERE id = ?"
        );
        $stmt->execute([$tenLop, $id]);

        header("Location: quan_ly_lop.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa lớp học</title>
</head>

<body>

    <h2>SỬA LỚP HỌC</h2>

    <p style="color:red;">
        <?= htmlspecialchars($thongBao) ?>
    </p>

    <form method="POST">
        <label>Tên lớp:</label>

        <input
            type="text"
            name="ten_lop"
            value="<?= htmlspecialchars($lop["ten_lop"]) ?>"
            required>

        <button type="submit">Cập nhật</button>
    </form>

    <p>
        <a href="quan_ly_lop.php">Quay lại quản lý lớp học</a>
    </p>

</body>

</html>