
<?php
require_once "connect.php";

// Kiểm tra tài khoản admin đã tồn tại chưa
$stmt = $conn->prepare(
    "SELECT id FROM users WHERE username = :username"
);

$stmt->execute([
    "username" => "admin"
]);

if ($stmt->fetch()) {
    die("Tai khoan admin da ton tai!");
}

// Tạo tài khoản admin
$username = "admin";
$password = password_hash("Admin@123", PASSWORD_DEFAULT);
$hoTen = "Quan tri vien";

$sql = "INSERT INTO users (username, password, ho_ten, role)
        VALUES (:username, :password, :ho_ten, :role)";

$stmt = $conn->prepare($sql);

$stmt->execute([
    "username" => $username,
    "password" => $password,
    "ho_ten" => $hoTen,
    "role" => "admin"
]);

echo "Tao tai khoan Admin thanh cong!";
?>
