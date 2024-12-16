<?php ob_start();
include "layout/header.php";
if (isset($_POST['submit'])) {
    try {

        $user_id = $_SESSION['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        function isEmailExists($conn, $email)
        {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
        }

        if (isEmailExists($conn, $email)) {
            $_SESSION['message_type'] = "error";
            $_SESSION['message']  = "Email đã được sử dụng. Vui lòng chọn email khác.";
        } else {
            // Cập nhật dữ liệu vào cơ sở dữ liệu
            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, email = :email WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name, 'phone' => $phone, 'email' => $email, 'id' => $user_id]);

            $_SESSION['message_type'] = "success";
            $_SESSION['message'] = "Cập nhật thông tin thành công.";

            header("Location: thongtin.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Lỗi cập nhật: " . $e->getMessage();
    }
}
?>
<!-- Content Begin-->
<div class="page-template noneBackground">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar">
                <a href="#" class="active">Tài khoản của tôi</a>
                <a href="user_account/dangxuat.php">Đăng xuất</a>
            </nav>

            <main class="col-md-9 col-lg-10">
                <div class="page-template noneBackground">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-template__knowledge">
                                    <h1>Tài khoản của tôi</h1>
                                    <div class="content-page">
                                        <p></p>
                                        <div class="gE iv gt">
                                            <?php if (isset($_SESSION['message'])){ ?>
                                                <?php $messageType = $_SESSION['message_type'] ?? 'success'; ?>
                                                <div class="alert alert-<?= $messageType ?>" id="messageBox">
                                                    <?= $_SESSION['message']; ?>
                                                </div>
                                                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                                            <?php } ?>

                                            <form method="POST" id="editForm" action="thongtin.php">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="firstName" class="form-label">Họ</label>
                                                        <input type="text" class="form-control" id="firstName" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" placeholder="Họ" disabled>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="lastName" class="form-label">Tên</label>
                                                        <input type="text" class="form-control" id="lastName" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" placeholder="Tên" disabled>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label for="phone" class="form-label">Số điện thoại</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" placeholder="Số điện thoại" disabled>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Email" disabled>
                                                    </div>
                                                </div>
                                                <button type="button" id="btnEdit" class="btn btn-primary" onclick="enableEditMode()">Chỉnh sửa</button>
                                                <button type="submit" id="btnSave" name="submit" class="btn btn-success d-none">Lưu</button>
                                                <button type="button" id="btnCancel" class="btn btn-secondary d-none" onclick="disableEditMode()">Hủy</button>
                                            </form>
                                        </div>
                                        <div class="">
                                            <div id=":mo" class="ii gt"></div>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<!-- Content End -->
<?php include "layout/footer.php";
ob_end_flush(); ?>