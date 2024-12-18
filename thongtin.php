    <?php ob_start();
    include "layout/header.php";
    $user_id = $_SESSION['user_id'];
    if (isset($_POST['submit'])) {
        try {

            if (!$user) {
                throw new Exception("Người dùng không tồn tại.");
            }

            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];

            $fieldsToUpdate = [];
            $params = ['id' => $user_id];

            if ($first_name !== $user['first_name']) {
                $fieldsToUpdate[] = "first_name = :first_name";
                $params['first_name'] = $first_name;
            }

            if ($last_name !== $user['last_name']) {
                $fieldsToUpdate[] = "last_name = :last_name";
                $params['last_name'] = $last_name;
            }

            if ($phone !== $user['phone']) {
                $fieldsToUpdate[] = "phone = :phone";
                $params['phone'] = $phone;
            }

            if ($email !== $user['email']) {
                $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = :email AND id != :id");
                $stmt->execute(['email' => $email, 'id' => $user_id]);
                if ($stmt->fetch()) {
                    $_SESSION['message_type'] = "error";
                    $_SESSION['message'] = "Email đã được sử dụng. Vui lòng chọn email khác.";
                    header("Location: thongtin.php");
                    exit;
                }
                $fieldsToUpdate[] = "email = :email";
                $params['email'] = $email;
            }

            if (!empty($fieldsToUpdate)) {
                $sql = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);

                $_SESSION['message_type'] = "success";
                $_SESSION['message'] = "Cập nhật thông tin thành công.";
            } else {
                $_SESSION['message_type'] = "info";
                $_SESSION['message'] = "Không có thông tin nào được thay đổi.";
            }

            header("Location: thongtin.php");
            exit;
        } catch (PDOException $e) {
            echo "Lỗi cập nhật: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    ?>
    <!-- Content Begin-->
    <div class="page-template noneBackground">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 sidebar">
                    <a href="#" id="accountLink" onclick="showAccountInfo()" class="active">Tài khoản của tôi</a>
                    <a href="#" id="ordersLink" onclick="showOrders()">Đơn hàng của tôi</a>
                    <a href="user_account/dangxuat.php">Đăng xuất</a>
                </nav>

                <main class="col-md-9 col-lg-10">
                    <div class="page-template noneBackground">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-template__knowledge">
                                        <div id="accountInfo">
                                            <h1>Tài khoản của tôi</h1>
                                            <div class="content-page">
                                                <p></p>
                                                <div class="gE iv gt">
                                                    <?php if (isset($_SESSION['message'])) { ?>
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
                                        <div id="orderList" class="d-none">
                                            <h2>Danh sách đơn hàng</h2>
                                            <div class="content-page">
                                                <?php
                                                try {
                                                    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
                                                    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                } catch (PDOException $e) {
                                                    echo "<p>Lỗi: " . $e->getMessage() . "</p>";
                                                    $orders = [];
                                                }

                                                if (!empty($orders)) {
                                                    echo '<table class="table table-bordered">';
                                                    echo '<thead>';
                                                    echo '<tr><th>Mã đơn hàng</th><th>Ngày đặt</th><th>Số lượng</th><th>Tổng tiền</th><th>Trạng thái</th></tr>';
                                                    echo '</thead>';
                                                    echo '<tbody>';
                                                    foreach ($orders as $order) {
                                                        $status = '';
                                                        if (isset($order['status'])) {
                                                            switch ($order['status']) {
                                                                case 'processing':
                                                                    $status = 'Đang xác nhận';
                                                                    break;
                                                                case 'confirmed':
                                                                    $status = 'Đã xác nhận';
                                                                    break;
                                                                case 'shipping':
                                                                    $status = 'Đang giao hàng';
                                                                    break;
                                                                case 'delivered':
                                                                    $status = 'Đã giao hàng';
                                                                    break;
                                                                case 'canceled':
                                                                    $status = 'Đã huỷ';
                                                                    break;
                                                                default:
                                                                    $status = 'Không rõ';
                                                                    break;
                                                            }
                                                        } else {
                                                            $status = 'Không rõ';
                                                        }
                                                        echo '<tr>';
                                                        echo '<td>' . htmlspecialchars($order['id']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($order['created_at']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($order['total_quantity']) . '</td>';
                                                        echo '<td>' . number_format($order['total_price'], 0, ',', '.') . 'đ</td>';
                                                        echo '<td>' . htmlspecialchars($status) . '</td>';
                                                        echo '</tr>';
                                                    }
                                                    echo '</tbody>';
                                                    echo '</table>';
                                                } else {
                                                    echo '<p>Bạn chưa có đơn hàng nào.</p>';
                                                }
                                                ?>
                                            </div>
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