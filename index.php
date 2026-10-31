<?php
require_once "auth.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "connect.php";

// 1. Lấy từ khóa tìm kiếm
$tuKhoa = trim($_GET["keyword"] ?? "");

// 2. Lấy danh sách sinh viên và tên lớp
$sql = "SELECT
            sinh_vien.*,
            lop_hoc.ten_lop
        FROM sinh_vien
        LEFT JOIN lop_hoc
            ON sinh_vien.lop_id = lop_hoc.id
        WHERE sinh_vien.ho_ten LIKE :name_keyword
           OR sinh_vien.email LIKE :email_keyword
        ORDER BY sinh_vien.id DESC";

$stmt = $conn->prepare($sql);

$search = "%" . $tuKhoa . "%";

$stmt->execute([
    ":name_keyword" => $search,
    ":email_keyword" => $search
]);

$danhSach = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
</head>

<body>
    <h2>DANH SÁCH SINH VIÊN</h2>

    <p>
        Xin chào,
        <strong>
            <?= htmlspecialchars($_SESSION["ho_ten"]) ?>
        </strong>
        |
        <a href="logout.php"
            onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
            Đăng xuất
        </a>
    </p>
    <!-- TÌM KIẾM SINH VIÊN -->
    <form method="GET" action="index.php">

        <input
            type="text"
            name="keyword"
            placeholder="Nhập tên hoặc email sinh viên..."
            value="<?= htmlspecialchars($tuKhoa) ?>">

        <button type="submit">Tìm kiếm</button>

        <a href="index.php">Làm mới</a>

    </form>

    <br>

    <!-- THÊM SINH VIÊN -->
    <a href="them.php">+ Thêm sinh viên</a>

    <br><br>

    <!-- BẢNG DANH SÁCH SINH VIÊN -->
    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Tuổi</th>
            <th>Lớp học</th>
            <th>Ngày tạo</th>
            <th>Chức năng</th>
        </tr>

        <?php if (count($danhSach) > 0): ?>

            <?php foreach ($danhSach as $sv): ?>

                <tr>

                    <td>
                        <?= $sv['id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($sv['ho_ten']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($sv['email']) ?>
                    </td>

                    <td>
                        <?= $sv['tuoi'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($sv['ten_lop'] ?? 'Chưa có lớp') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($sv['ngay_tao']) ?>
                    </td>

                    <td>

                        <!-- SỬA SINH VIÊN -->
                        <a href="sua.php?id=<?= $sv['id'] ?>">
                            Sửa
                        </a>

                        |

                        <!-- XÓA SINH VIÊN -->
                        <form
                            action="xoa.php"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $sv['id'] ?>">

                            <button type="submit">
                                Xóa
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">
                    Không tìm thấy sinh viên!
                </td>
            </tr>

        <?php endif; ?>

    </table>

</body>

</html>