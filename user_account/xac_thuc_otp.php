<?php
session_start();
$error = '';
$success = '';

if (!isset($_SESSION['email']) || !isset($_SESSION['action'])) {
    header("Location: dangky.php");
    exit;
}
$email = $_SESSION['email'];
$action = $_SESSION['action'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_input = trim($_POST['otp']);

    if (empty($otp_input)) {
        $error = "Vui lòng nhập mã OTP.";
    } else {
        require '../database/connect.php';

        $stmt = $conn->prepare("SELECT otp, verify FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['otp'] === $otp_input) {
                if ($action === 'register') {
                    $update_stmt = $conn->prepare("UPDATE users SET verify = 1, otp = NULL WHERE email = :email");
                    $update_stmt->bindParam(":email", $email);
                    $update_stmt->execute();
                    
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    unset($_SESSION['email']);
                    unset($_SESSION['action']);
                    header("Location: ../index.php");   
                    exit;
                } elseif ($action === 'resetpass') {
                    unset($_SESSION['action']);
                    header("Location: resetpass.php");
                    exit;
                }
            } else {
                $error = "Mã OTP không chính xác. Vui lòng thử lại.";
            }
        } else {
            $error = "Không tìm thấy người dùng.";
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
    <title>Xác Thực OTP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <div class="form-container-dangky">
        <h2>Xác Thực OTP</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="xac_thuc_otp.php" method="post">
            <label for="otp">Nhập mã OTP đã gửi đến email của bạn*</label>
            <input type="text" id="otp" name="otp" placeholder="Nhập mã OTP" required>
            <button type="submit">Xác Thực</button>
        </form>
    </div>
</body>

</html>