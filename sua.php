<?php
require_once "connect.php";

// Lấy ID sinh viên
$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    die("ID không hợp lệ!");
}

// Lấy thông tin sinh viên
$sql = "SELECT * FROM sinh_vien WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

$sv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sv) {
    die("Không tìm thấy sinh viên!");
}

// Lấy danh sách lớp học
$sql = "SELECT * FROM lop_hoc ORDER BY id ASC";
$stmt = $conn->query($sql);
$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $hoTen = trim($_POST["ho_ten"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $tuoi = (int)($_POST["tuoi"] ?? 0);
    $lopId = (int)($_POST["lop_id"] ?? 0);

    if (
        $hoTen === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $tuoi <= 0
    ) {
        die("Thông tin không hợp lệ!");
    }

    $sql = "UPDATE sinh_vien
            SET ho_ten = ?,
                email = ?,
                tuoi = ?,
                lop_id = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        $hoTen,
        $email,
        $tuoi,
        $lopId > 0 ? $lopId : null,
        $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
</head>

<body>

    <h2>CẬP NHẬT SINH VIÊN</h2>

    <form method="POST">

        <label>Họ tên:</label><br>
        <input
            type="text"
            name="ho_ten"
            value="<?= htmlspecialchars($sv['ho_ten']) ?>"
            required>
        <br><br>

        <label>Email:</label><br>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($sv['email']) ?>"
            required>
        <br><br>

        <label>Tuổi:</label><br>
        <input
            type="number"
            name="tuoi"
            value="<?= $sv['tuoi'] ?>"
            min="1"
            required>
        <br><br>

        <label>Lớp học:</label><br>

        <select name="lop_id">

            <option value="0">-- Chưa chọn lớp --</option>

            <?php foreach ($danhSachLop as $lop): ?>

                <option
                    value="<?= $lop['id'] ?>"
                    <?= ($sv['lop_id'] == $lop['id']) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($lop['ten_lop']) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit">Cập nhật</button>

        <a href="index.php">Quay lại</a>

    </form>

</body>

</html>