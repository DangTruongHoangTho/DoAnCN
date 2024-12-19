<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Địa chỉ email không hợp lệ.";
    } else {
        session_start();
        require '../database/connect.php';
        require '../database/function.php';

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $hashed_password = hash('sha256', $password);
        if ($user) {

            if ($hashed_password === $user['password_hash']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                loadCartFromDatabase($user['id'], $conn);
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Sai mật khẩu. Vui lòng thử lại.";
            }
        } else {
            $error = "Email không tồn tại trong hệ thống.";
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
    <title>Đăng Nhập</title>
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
    <style>
        .header-right {
            padding-top: 20px;
            text-align: center !important;
        }
    </style>
</head>

<body>
    <div class="header-right">
        <a href="../index.php" class="brand"><img src="../images/banner/LogoT&T.png" alt="Logo" title="logo" /></a>
    </div>
    <div class="form-container-dangnhap">
        <h2>Đăng Nhập</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="dangnhap.php" method="post">

            <label for="email">Email*</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?> " placeholder="Email" required>

            <label for="password">Mật khẩu*</label>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            <div class="checkbox-container">
                <input type="checkbox" id="show_password" onclick="displayPass()" />
                <label for="show_password">Hiện mật khẩu</label>
            </div>
            <div><a href="quenmk.php" class="">Quên mật khẩu?</a></div>

            <button type="submit">Đăng Nhập</button>
        </form>
    </div>
    <div class="dangky">
        <h2>Thành viên mới?</h2>
        <p>Trở thành thành viên của T&T Store<br>Để nhận những ưu đãi và dịch vụ bất ngờ</p>
        <a href="dangky.php" class="register-button">Đăng Ký</a>
    </div>
    <?php include "../layout/footer.php"; ?>