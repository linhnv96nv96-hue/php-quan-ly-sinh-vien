<?php
require_once "admin.php";
require_once "connect.php";

$id = $_GET["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID tài khoản không hợp lệ!");
}

// Lấy tài khoản cần sửa
$stmt = $conn->prepare(
    "SELECT id, username, ho_ten, role FROM users WHERE id = ?"
);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Không tìm thấy tài khoản!");
}

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $hoTen = trim($_POST["ho_ten"] ?? "");
    $role = $_POST["role"] ?? "";

    if ($username === "" || $hoTen === "") {
        $thongBao = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (!in_array($role, ["admin", "nhanvien"], true)) {
        $thongBao = "Vai trò không hợp lệ!";
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE users
                 SET username = ?, ho_ten = ?, role = ?
                 WHERE id = ?"
            );

            $stmt->execute([$username, $hoTen, $role, $id]);

            header("Location: quan_ly_tai_khoan.php");
            exit;
        } catch (PDOException $e) {
            $thongBao = "Tên đăng nhập đã tồn tại hoặc có lỗi khi cập nhật!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa tài khoản</title>
</head>

<body>

    <h2>SỬA THÔNG TIN TÀI KHOẢN</h2>

    <p style="color:red;">
        <?= htmlspecialchars($thongBao) ?>
    </p>

    <form method="POST">
        <p>
            Tên đăng nhập:
            <input type="text" name="username"
                value="<?= htmlspecialchars($user["username"]) ?>"
                required>
        </p>

        <p>
            Họ tên:
            <input type="text" name="ho_ten"
                value="<?= htmlspecialchars($user["ho_ten"]) ?>"
                required>
        </p>

        <p>
            Vai trò:
            <select name="role" required>
                <option value="admin"
                    <?= $user["role"] === "admin" ? "selected" : "" ?>>
                    Admin
                </option>

                <option value="nhanvien"
                    <?= $user["role"] === "nhanvien" ? "selected" : "" ?>>
                    Nhân viên
                </option>
            </select>
        </p>

        <button type="submit">Cập nhật</button>
    </form>

    <p>
        <a href="quan_ly_tai_khoan.php">Quay lại quản lý tài khoản</a>
    </p>

</body>

</html>