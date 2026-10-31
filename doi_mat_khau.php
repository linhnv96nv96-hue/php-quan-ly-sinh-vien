<?php
require_once "admin.php";
require_once "connect.php";

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID tài khoản không hợp lệ!");
}

// Kiểm tra tài khoản
$stmt = $conn->prepare(
    "SELECT id, username FROM users WHERE id = ?"
);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Không tìm thấy tài khoản!");
}

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matKhauMoi = $_POST["mat_khau_moi"] ?? "";
    $xacNhan = $_POST["xac_nhan"] ?? "";

    if (strlen($matKhauMoi) < 8) {
        $thongBao = "Mật khẩu phải có ít nhất 8 ký tự!";
    } elseif ($matKhauMoi !== $xacNhan) {
        $thongBao = "Mật khẩu xác nhận không khớp!";
    } else {
        $passwordHash = password_hash($matKhauMoi, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "UPDATE users SET password = ? WHERE id = ?"
        );
        $stmt->execute([$passwordHash, $id]);

        header("Location: quan_ly_tai_khoan.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
</head>

<body>

    <h2>ĐỔI MẬT KHẨU</h2>

    <p>Tài khoản: <strong><?= htmlspecialchars($user["username"]) ?></strong></p>

    <p style="color:red;">
        <?= htmlspecialchars($thongBao) ?>
    </p>

    <form method="POST">
        <p>
            Mật khẩu mới:
            <input type="password" name="mat_khau_moi"
                minlength="8" required>
        </p>

        <p>
            Xác nhận mật khẩu:
            <input type="password" name="xac_nhan" required>
        </p>

        <button type="submit">Cập nhật mật khẩu</button>
    </form>

    <p>
        <a href="quan_ly_tai_khoan.php">Quay lại quản lý tài khoản</a>
    </p>

</body>

</html>