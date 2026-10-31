
<?php
require_once "auth.php";

// Chỉ cho phép Admin truy cập
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    die("Ban khong co quyen truy cap chuc nang nay!");
}
?>
