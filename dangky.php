<?php
// Xử lý dữ liệu khi form được gửi
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra các trường dữ liệu
    if (empty($last_name) || empty($first_name) || empty($email) || empty($phone) || empty($password)) {
        $error = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Địa chỉ email không hợp lệ.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu không khớp.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Số điện thoại phải là 10 chữ số.";
    } else {
        // Kết nối cơ sở dữ liệu
        require 'database/conect.php';

        function isEmailExists($conn, $email)
        {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
        }
        $email = $_POST['email'];        
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $dob = $_POST['dob'];

        if (isEmailExists($conn, $email)) {
            $error = "Email đã được sử dụng. Vui lòng chọn email khác.";
        } else {
            // Mã hóa mật khẩu và thêm vào cơ sở dữ liệu
            $hashed_password = hash('sha256', $password);

            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, dob) 
                            VALUES (:first_name, :last_name, :email, :phone, :password, :dob)");
            $stmt->bindParam(":first_name", $first_name);
            $stmt->bindParam(":last_name", $last_name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":dob", $dob);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công!";
            } else {
                $error = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        }

        // Đóng kết nối (không cần thiết vì PDO tự đóng khi script kết thúc)
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
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
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="dangnhap">
        <h2>Đã là thành viên?</h2>
        <p>Đăng nhập để truy cập vào tài khoản của bạn</p>
        <a href="dangnhap.php" class="dangnhap-button">Đăng Nhập</a>
    </div>
    <div class="form-container-dangky">
        <h2>Đăng Ký</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="dangky.php" method="post">
            <label for="first_name">Họ*</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($first_name ?? '') ?>" placeholder="Họ" required>

            <label for="last_name">Tên*</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($last_name ?? '') ?>" placeholder="Tên" required>

            <label for="email">Email*</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="Email" required>

            <label for="phone">Số điện thoại*</label>
            <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" placeholder="Số điện thoại" required>

            <label for="password">Mật khẩu*</label>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required>

            <label for="confirm_password">Nhập lại mật khẩu*</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
            <div class="checkbox-container">
                <input type="checkbox" id="show_password" onclick="displayPass()" />
                <label for="show_password">Hiện mật khẩu</label>
            </div>
            <button type="submit">Đăng Ký</button>
        </form>
    </div>
    <?php include "layout/footer.php"; ?>