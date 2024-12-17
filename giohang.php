<?php
include "layout/header_giohang.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $product_id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $_SESSION['cart'][$product_id] = $quantity;
}

$total = 0;
?>
<main>
    <div class="container-cart">
        <h1 class="my-4">Giỏ hàng</h1>
        <p>(<?= getCartTotalItems() ?>) sản phẩm</p>

        <!-- Sử dụng Bootstrap Grid System -->
        <div class="row">
            <!-- Cột sản phẩm -->
            <div class="col-md-8">
                <div class="cart-item border-bottom">
                    <div class="row align-items-center">
                        <!-- Cột 1: Hình ảnh sản phẩm -->
                        <div class="col-3">
                            <img src="product1.jpg" alt="Sản phẩm 1" class="img-fluid">
                        </div>

                        <!-- Cột 2: Thông tin chi tiết sản phẩm -->
                        <div class="col-3">
                            <h5 class="mb-1 font-weight-bold small-heading"></h5>
                            <p class="mb-1 small-text"></p>
                            <p class="text-muted small-text">Mã hàng: </p>
                        </div>

                        <!-- Cột 3: Giá sản phẩm -->
                        <div class="col-2 text-center">
                            <p class="mb-0 text-muted"><s></s></p>
                            <p class="mb-0 text-danger font-weight-bold"></p>
                        </div>

                        <!-- Cột 4: Số lượng -->
                        <div class="product__details__quantity col-2.5 text-center">
                            <div> Số lượng</div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="pro-qty">
                                        <input type="text" id="quantity" name="quantity" min="1" value="1" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Xóa sản phẩm -->
                        <div class="col-1 text-center">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <div class="remove-item text-danger" style="cursor: pointer;">✕</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Thêm các sản phẩm khác tương tự ở đây -->
            </div>

            <!-- Cột tổng kết giỏ hàng -->
            <div class="col-md-4">
                <div class="cart-summary border p-3">
                    <h5 class="mb-3">Tổng kết giỏ hàng</h5>
                    <div class="totals">
                        <p class="d-flex justify-content-between">Tạm tính: <span></span></p>
                        <p class="d-flex justify-content-between">Phí vận chuyển: <span>Free</span></p>
                        <hr>
                        <p class="d-flex justify-content-between total-price text-danger font-weight-bold">
                            Tổng: <span></span>
                        </p>
                    </div>
                    <button class="btn btn-block mt-3">Thanh toán</button>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include "layout/footer.php"; ?>