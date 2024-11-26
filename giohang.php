<?php
session_start();
// include 'db.php';

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

<?php include "layout/header.php"; ?>
    <header>
        <h1>Your Shopping Cart</h1>
    </header>
    <main>
        <div class="cart">
            <?php foreach ($_SESSION['cart'] as $id => $quantity): ?>
                <?php
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                $item_total = $product['price'] * $quantity;
                $total += $item_total;
                ?>
                <div class="cart-item">
                    <h2><?php echo $product['name']; ?></h2>
                    <p>Quantity: <?php echo $quantity; ?></p>
                    <p>Price: $<?php echo number_format($item_total, 2); ?></p>
                </div>
            <?php endforeach; ?>
            <h3>Total: $<?php echo number_format($total, 2); ?></h3>
            <a href="checkout.php">Proceed to Checkout</a>
        </div>
    </main>
<?php include "layout/footer.php"; ?>