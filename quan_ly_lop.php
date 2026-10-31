<?php
require_once "admin.php";
require_once "connect.php";

$thongBao = "";

// Thêm lớp học
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenLop = trim($_POST["ten_lop"] ?? "");

    if ($tenLop === "") {
        $thongBao = "Vui lòng nhập tên lớp!";
    } else {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO lop_hoc (ten_lop) VALUES (?)"
            );
            $stmt->execute([$tenLop]);

            $thongBao = "Thêm lớp học thành công!";
        } catch (PDOException $e) {
            $thongBao = "Không thể thêm lớp học. Vui lòng kiểm tra tên lớp!";
        }
    }
}

// Lấy danh sách lớp
$stmt = $conn->query(
    "SELECT l.id, l.ten_lop,
            COUNT(s.id) AS so_sinh_vien
     FROM lop_hoc l
     LEFT JOIN sinh_vien s ON s.lop_id = l.id
     GROUP BY l.id, l.ten_lop
     ORDER BY l.id DESC"
);

$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý lớp học</title>
</head>

<body>

    <h2>QUẢN LÝ LỚP HỌC</h2>

    <p><?= htmlspecialchars($thongBao) ?></p>

    <form method="POST">
        <label>Tên lớp:</label>
        <input type="text" name="ten_lop" required>
        <button type="submit">Thêm lớp</button>
    </form>

    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tên lớp</th>
            <th>Số sinh viên</th>
            <th>Chức năng</th>
        </tr>

        <?php foreach ($danhSachLop as $lop): ?>
            <tr>
                <td><?= $lop["id"] ?></td>

                <td><?= htmlspecialchars($lop["ten_lop"]) ?></td>

                <td><?= $lop["so_sinh_vien"] ?></td>

                <td>
                    <a href="sua_lop.php?id=<?= $lop["id"] ?>">Sửa</a>

                    |

                    <form action="xoa_lop.php" method="POST" style="display:inline;"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa lớp này?');">

                        <input type="hidden" name="id" value="<?= $lop["id"] ?>">

                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>

    <a href="index.php">Quay lại danh sách sinh viên</a>

</body>

</html>