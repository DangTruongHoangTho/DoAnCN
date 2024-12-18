<?php
ob_start();
include "layout/header_giohang.php";
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['id'];
    $new_quantity = intval($_POST['quantity']);

    if ($new_quantity > 0) {
        $_SESSION['cart'][$product_id] = $new_quantity;
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                if ($new_quantity > $item['quantity']) {
                    $item['quantity'] += $new_quantity;
                } else {
                    $item['quantity'] -= $new_quantity;
                }
                $found = true;
                break;
            }
        }
        $sqlUpdate = "UPDATE carts SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmtUpdate->execute();
        header("Location: giohang.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product_id'])) {
    $delete_product_id = $_POST['delete_product_id'];

    if (isset($_SESSION['cart'][$delete_product_id])) {
        unset($_SESSION['cart'][$delete_product_id]);
    }
    $sqlDelete = "DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtDelete->bindParam(':product_id', $delete_product_id, PDO::PARAM_INT);
    $stmtDelete->execute();

    header("Location: giohang.php");
    exit();
}
ob_end_flush();
?>
<main>
    <div class="container-cart">
        <h1 class="my-4">Giỏ hàng</h1>
        <p>(<?= getCartTotalItems() ?>) sản phẩm</p>
        <?php if (!empty($cartItems)) { ?>
            <div class="row">
                <div class="col-md-8">
                    <form method="POST" action="" style="display:inline;">
                        <?php foreach ($cartItems  as $item) { ?>
                            <div class="cart-item border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <?php
                                        $productSlug = removeAccents($item['product_name']);
                                        echo "<a class='pro-a-href' href='chitietSP.php?id={$item['product_id']}&slug={$productSlug}'>";
                                        if (!empty($item['first_image'])) {
                                            $categoryName = removeAccents($item['category_name']);
                                            $brandName = removeAccents($item['brand_name']);
                                            $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
                                            $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));
                                            $imagePath = "./images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . htmlspecialchars(trim($item['first_image']));
                                        ?>
                                            <img
                                                src="<?php echo $imagePath; ?>"
                                                alt=""
                                                class="img-fluid" />
                                        <?php echo "</a>";
                                        } ?>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="mb-1 font-weight-bold small-heading"><?php echo $item['brand_name'] ?></h5>
                                        <p class="mb-1 small-text"><?php echo $item['product_name'] ?></p>
                                        <p class="text-muted small-text">Mã hàng: <?php echo $item['product_id'] ?></p>
                                    </div>
                                    <div class="col-2 text-center">
                                        <p class="mb-0 text-muted"><s><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</s></p>
                                        <p class="mb-0 text-danger font-weight-bold"><?php echo number_format($item['discounted_price'], 0, ',', '.'); ?>đ</p>
                                    </div>

                                    <div class="product__details__quantity col-2.5 text-center">
                                        <div> Số lượng</div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <div class="pro-qty">
                                                    <input type="text" id="quantity" name="quantity" min="1" value="<?php echo $item['quantity'] ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center">
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <input type="hidden" name="delete_product_id" value="<?php echo $item['product_id']; ?>">
                                                <button type="submit" class="remove-item text-danger" style="border:none; background:none; cursor:pointer;">✕</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="cart-summary border p-3">
                        <h5 class="mb-3">Tổng kết giỏ hàng</h5>
                        <div class="totals">
                            <p class="d-flex justify-content-between">Tạm tính: <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span></p>
                            <p class="d-flex justify-content-between">Phí vận chuyển: <span>Miễn phí</span></p>
                            <hr>
                            <p class="d-flex justify-content-between total-price text-danger font-weight-bold">
                                Tổng: <span><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                            </p>
                        </div>
                        <form method="GET" action="order.php">
                            <?php foreach ($cartItems as $item) { ?>
                                <input type="hidden" name="id[]" value="<?php echo $item['product_id']; ?>">
                                <input type="hidden" name="quantity[]" value="<?php echo $item['quantity']; ?>">
                            <?php } ?>
                            <button type="submit" class="btn btn-block mt-3">Thanh toán</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p>Giỏ hàng của bạn đang trống.</p>
        <?php } ?>
    </div>
</main>
<?php include "layout/footer.php"; ?>