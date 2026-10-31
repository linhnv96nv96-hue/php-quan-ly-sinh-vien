
<?php
session_start();

// Xóa toàn bộ dữ liệu đăng nhập
$_SESSION = [];

// Hủy session
session_destroy();

// Quay về trang đăng nhập
header("Location: login.php");
exit;
?>
