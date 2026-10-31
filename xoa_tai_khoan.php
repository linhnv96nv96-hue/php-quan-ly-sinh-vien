
<?php
require_once "admin.php";
require_once "connect.php";

// Chỉ xử lý khi gửi bằng POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quan_ly_tai_khoan.php");
    exit;
}

$id = $_POST["id"] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID tài khoản không hợp lệ!");
}

// Không cho tự xóa tài khoản đang đăng nhập
if ((int)$id === (int)$_SESSION["user_id"]) {
    die("Bạn không thể xóa tài khoản đang đăng nhập!");
}

try {
    // Bắt đầu giao dịch
    $conn->beginTransaction();

    // Khóa các dòng tài khoản trong lúc kiểm tra và xóa
    $stmt = $conn->query("SELECT id FROM users FOR UPDATE");
    $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Kiểm tra tài khoản cần xóa
    $stmt = $conn->prepare(
        "SELECT id, role FROM users WHERE id = ?"
    );
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Không tìm thấy tài khoản!");
    }

    // Nếu là Admin, kiểm tra số Admin còn lại
    if ($user["role"] === "admin") {
        $stmt = $conn->query(
            "SELECT COUNT(*) FROM users WHERE role = 'admin'"
        );

        $soAdmin = (int)$stmt->fetchColumn();

        if ($soAdmin <= 1) {
            throw new Exception(
                "Không thể xóa Admin cuối cùng trong hệ thống!"
            );
        }
    }

    // Xóa tài khoản
    $stmt = $conn->prepare(
        "DELETE FROM users WHERE id = ?"
    );
    $stmt->execute([$id]);

    // Xác nhận giao dịch
    $conn->commit();

    header("Location: quan_ly_tai_khoan.php");
    exit;
} catch (Exception $e) {

    // Nếu có lỗi thì hoàn tác
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    die("Lỗi: " . htmlspecialchars($e->getMessage()));
}
?>
