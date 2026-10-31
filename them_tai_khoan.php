<?php
require_once "admin.php";
require_once "connect.php";

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $hoTen = trim($_POST["ho_ten"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "nhanvien";

    if ($username === "" || $hoTen === "" || $password === "") {
        $thongBao = "Vui lòng nhập đầy đủ thông tin!";
    } elseif (strlen($password) < 8) {
        $thongBao = "Mật khẩu phải có ít nhất 8 ký tự!";
    } elseif (!in_array($role, ["admin", "nhanvien"], true)) {
        $thongBao = "Vai trò không hợp lệ!";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users (username, password, ho_ten, role)
                    VALUES (:username, :password, :ho_ten, :role)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":username" => $username,
                ":password" => $passwordHash,
                ":ho_ten" => $hoTen,
                ":role" => $role
            ]);

            $thongBao = "Tạo tài khoản thành công!";
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $thongBao = "Tên đăng nhập đã tồn tại!";
            } else {
                $thongBao = "Có lỗi xảy ra khi tạo tài khoản.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm tài khoản</title>
</head>

<body>

    <h2>THÊM TÀI KHOẢN</h2>

    <p><?= htmlspecialchars($thongBao) ?></p>

    <form method="POST">
        <p>
            Tên đăng nhập:<br>
            <input type="text" name="username" required>
        </p>

        <p>
            Họ tên:<br>
            <input type="text" name="ho_ten" required>
        </p>

        <p>
            Mật khẩu:<br>
            <input type="password" name="password" minlength="8" required>
        </p>

        <p>
            Vai trò:<br>
            <select name="role">
                <option value="nhanvien">Nhân viên</option>
                <option value="admin">Admin</option>
            </select>
        </p>

        <button type="submit">Tạo tài khoản</button>
    </form>

    <p>
        <a href="quan_ly_tai_khoan.php">Quay lại quản lý tài khoản</a>
    </p>

</body>

</html>