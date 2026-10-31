<?php
require_once "connect.php";

// Lấy danh sách lớp học
$sql = "SELECT * FROM lop_hoc ORDER BY id ASC";
$stmt = $conn->query($sql);
$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý thêm sinh viên
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

    // Thêm sinh viên kèm khóa ngoại lop_id
    $sql = "INSERT INTO sinh_vien
            (ho_ten, email, tuoi, lop_id)
            VALUES (:ho_ten, :email, :tuoi, :lop_id)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":ho_ten" => $hoTen,
        ":email" => $email,
        ":tuoi" => $tuoi,
        ":lop_id" => $lopId > 0 ? $lopId : null
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
</head>

<body>

    <h2>THÊM SINH VIÊN</h2>

    <form method="POST">

        <label>Họ tên:</label><br>
        <input type="text" name="ho_ten" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Tuổi:</label><br>
        <input type="number" name="tuoi" min="1" required>
        <br><br>

        <label>Lớp học:</label><br>

        <select name="lop_id">
            <option value="0">-- Chọn lớp học --</option>

            <?php foreach ($danhSachLop as $lop): ?>
                <option value="<?= $lop['id'] ?>">
                    <?= htmlspecialchars($lop['ten_lop']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <button type="submit">Thêm sinh viên</button>

        <a href="index.php">Quay lại</a>

    </form>

</body>

</html>