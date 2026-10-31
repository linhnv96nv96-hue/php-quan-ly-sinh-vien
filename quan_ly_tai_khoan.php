<?php
require_once "admin.php";
require_once "connect.php";

// Lấy danh sách tài khoản
$sql = "SELECT id, username, ho_ten, role, ngay_tao
        FROM users
        ORDER BY id DESC";

$stmt = $conn->query($sql);
$danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
</head>

<body>

    <h2>QUẢN LÝ TÀI KHOẢN</h2>

    <p>
        Xin chào,
        <strong><?= htmlspecialchars($_SESSION["ho_ten"]) ?></strong>
        |
        <a href="logout.php">Đăng xuất</a>
    </p>

    <p>
        <a href="index.php">Quay lại danh sách sinh viên</a>
        |
        <a href="them_tai_khoan.php">+ Thêm tài khoản</a>
    </p>

    <h3>Danh sách tài khoản</h3>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Họ tên</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th>Chức năng</th>
        </tr>

        <?php foreach ($danhSach as $user): ?>
            <tr>
                <td><?= $user["id"] ?></td>
                <td><?= htmlspecialchars($user["username"]) ?></td>
                <td><?= htmlspecialchars($user["ho_ten"]) ?></td>
                <td>
                    <?= $user["role"] === "admin" ? "Admin" : "Nhân viên" ?>
                </td>
                <td><?= htmlspecialchars($user["ngay_tao"]) ?></td>

                <td>
                    <a href="sua_tai_khoan.php?id=<?= $user["id"] ?>">Sửa</a>
                    |
                    <a href="doi_mat_khau.php?id=<?= $user["id"] ?>">Đổi mật khẩu</a>
                    |
                    <form action="xoa_tai_khoan.php" method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">

                        <input type="hidden" name="id" value="<?= $user["id"] ?>">

                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>