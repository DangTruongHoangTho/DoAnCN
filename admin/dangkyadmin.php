<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
    session_start();
    include 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);
        $type = trim($_POST['type']);
        $error = "";
        $success = "";
    
        if (empty($name) || empty($password)) {
            $error = "Tên đăng nhập và mật khẩu không được để trống!";
        } elseif (!in_array($type, ['admin', 'staff'])) {
            $error = "Loại tài khoản không hợp lệ!";
        } else {
            try {
                $stmt = $conn->prepare("SELECT id FROM admin WHERE name = :name");
                $stmt->execute(['name' => $name]);
                if ($stmt->fetch()) {
                    $error = "Tên đăng nhập đã tồn tại!";
                } else {
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("INSERT INTO admin (name, password_hash, status, type) 
                                            VALUES (:name, :password_hash, 'active', :type)");
                    $stmt->execute([
                        'name' => $name,
                        'password_hash' => $password_hash,
                        'type' => $type
                    ]);
                    $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
                }
            } catch (PDOException $e) {
                $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            width: 400px;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .register-container input, .register-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .register-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
        .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2>Đăng Ký</h2>
    
    <!-- Hiển thị lỗi nếu có -->
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Hiển thị thành công nếu đăng ký thành công -->
    <?php if (isset($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="name" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <select name="type" required>
            <option value="" disabled selected>Chọn loại tài khoản</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
        </select>
        <button type="submit">Đăng ký</button>
    </form>
    
    <div class="message">
        <p>Đã có tài khoản? <a href="dangnhapadmin.php">Đăng nhập ngay!</a></p>
    </div>
</div>
</body>
</html>