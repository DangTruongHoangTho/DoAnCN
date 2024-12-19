<?php
session_start();
$error = '';
$success = '';

if (!isset($_SESSION['email'])) {
    header("Location: dangky.php");
    exit;
}
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    if (empty($password) || empty($confirm_password)) {
        $error = "Vui lòng nhập đầy đủ mật khẩu.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự, bao gồm chữ hoa, chữ thường và số.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        require '../database/connect.php';
        $hashed_password = hash('sha256', $password);

        $stmt = $conn->prepare("UPDATE users SET password_hash = :password_hash, otp = NULL WHERE email = :email");
        $stmt->bindParam(":password_hash", $hashed_password);
        $stmt->bindParam(":email", $email);

        if ($stmt->execute()) {
            $success = "Mật khẩu đã được cập nhật thành công.";
            unset($_SESSION['email']);
            header("Location: dangnhap.php");
            exit;
        } else {
            $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link
        rel="website icon"
        type="png"
        href="../images/banner/LogoT&T_2.png"
        id="logo" />
    <!-- link ngoài -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <div class="form-container-dangky">
        <h2>Đổi mật khẩu</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="resetpass.php" method="post">

            <label for="password">Mật khẩu mới*</label>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            <div class="checkbox-container">
                <input type="checkbox" id="show_password" onclick="displayPass()" />
                <label for="show_password">Hiện mật khẩu</label>
            </div>
            <label for="confirm_password">Xác nhận lại mật khẩu*</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            <div class="checkbox-container">
                <input type="checkbox" id="show_password_confirm" onclick="displayPassConfirm()" />
                <label for="show_password">Hiện mật khẩu</label>
            </div>
            <button type="submit">Sửa mật khẩu</button>
        </form>
    </div>
    <?php include "../layout/footer.php"; ?>