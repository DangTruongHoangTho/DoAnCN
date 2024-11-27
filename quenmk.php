<?php
// Xử lý dữ liệu khi form được gửi
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Kiểm tra các trường dữ liệu
    if (empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ các trường bắt buộc.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Địa chỉ email không hợp lệ.";
    } else {
        // // Kết nối cơ sở dữ liệu
        // $servername = "localhost";
        // $username = "root";
        // $dbpassword = "";
        // $dbname = "user_database";

        // $conn = new mysqli($servername, $username, $dbpassword, $dbname);
        // if ($conn->connect_error) {
        //     die("Kết nối thất bại: " . $conn->connect_error);
        // }

        // // Kiểm tra email đã tồn tại
        // $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        // $stmt->bind_param("s", $email);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // if ($result->num_rows > 0) {
        //     $error = "Email đã được sử dụng. Vui lòng chọn email khác.";
        // } else {
        //     // Mã hóa mật khẩu và thêm vào cơ sở dữ liệu
        //     $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        //     $stmt = $conn->prepare("INSERT INTO users (last_name, first_name, email, phone, password, dob) VALUES (?, ?, ?, ?, ?, ?)");
        //     $stmt->bind_param("ssssss", $last_name, $first_name, $email, $phone, $hashed_password, $dob);

        //     if ($stmt->execute()) {
        //         $success = "Đăng ký thành công!";
        //     } else {
        //         $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        //     }
        // }

        // // Đóng kết nối
        // $stmt->close();
        // $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        .form-container {
            width: 400px;
            background: #fff;
            padding: 20px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: auto;
            margin-top: 15px;
            margin-bottom: 30px;

        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #d2232a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #3a393a;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .dangky {
            text-align: center;
            color: #fff;
            padding: 20px;
            background-color: #3a393a;
            margin-bottom: 100px;
        }

        .dangky h2 {
            margin-bottom: 10px;
            font-size: 24px;
        }

        .dangky p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .register-button {
            display: inline-block;
            padding: 10px 30px;
            background-color: #fff;
            color: #3a393a;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .register-button:hover {
            text-decoration: none;
            color: #3a393a;

        }
        .btn-cancel {
            display: inline-block; /* Để nó hiển thị như một khối nội tuyến */
            margin: 10px auto 0; /* Tự động căn giữa với margin */
            font-size: 14px;
            color: #007bff; /* Màu xanh */
            text-decoration: none;
            text-align: center;
        }
        .btn-cancel:hover {
            text-decoration: none;
        }
    </style>
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
    <div class="form-container">
        <h2>Đăng Nhập</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form action="quenmk.php" method="post">

            <label for="email">Email*</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="Email" required>
            
            <button type="submit">Gửi</button>
            <a href="dangnhap.php" class="btn-cancel">Hủy</a>
        </form>
    </div>
    <div class="dangky">
        <h2>Thành viên mới?</h2>
        <p>Trở thành thành viên của T&T Store<br>Để nhận những ưu đãi và dịch vụ bất ngờ</p>
        <a href="dangky.php" class="register-button">Đăng Ký</a>
    </div>
<?php include "layout/footer.php"; ?>