<?php
session_start();
require_once "connect.php";

// Nếu đã đăng nhập thì chuyển đến trang quản lý
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$thongBao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $thongBao = "Vui long nhap day du tai khoan va mat khau!";
    } else {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute(["username" => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["ho_ten"] = $user["ho_ten"];
            $_SESSION["role"] = $user["role"];

            header("Location: index.php");
            exit;
        } else {
            $thongBao = "Tai khoan hoac mat khau khong chinh xac!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Quản lý sinh viên Cuoi nam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            width: 350px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2563eb;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>ĐĂNG NHẬP</h2>

        <?php if ($thongBao !== ""): ?>
            <p class="error">
                <?= htmlspecialchars($thongBao) ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input
                type="text"
                name="username"
                placeholder="Tên đăng nhập"
                required>

            <input
                type="password"
                name="password"
                placeholder="Mật khẩu"
                required>

            <button type="submit">Đăng nhập</button>
        </form>
    </div>

</body>

</html>