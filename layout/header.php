<?php
session_start();
require 'database/connect.php';

$sql = "SELECT categories.name AS category_name, 
            brands.name AS brand_name FROM brands INNER JOIN categories
            ON brands.category_id = categories.id
            ORDER BY categories.name, brands.name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categoryBrands = [];
foreach ($categories as $category) {
    $categoryBrands[$category['category_name']][] = $category['brand_name'];
}
function removeAccents($string)
{
  $accents = [
    'a' => ['á', 'à', 'ả', 'ã', 'ạ', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'a'],
    'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'e'],
    'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'i'],
    'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'o'],
    'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'u'],
    'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'y'],
    'd' => ['đ', 'd'],
  ];

  foreach ($accents as $nonAccent => $accent) {
    $string = str_replace($accent, $nonAccent, $string);
  }

  $string = str_replace(' ', '-', $string);

  return $string;
}
function getCartItems()
{
    return $_SESSION['cart'] ?? [];
}

function getCartTotalItems()
{
    $totalItems = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItems += $item['quantity'];
        }
    }
    return $totalItems;
}

function getCartTotalPrice()
{
    $totalPrice = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
    }
    return $totalPrice;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>T&T Store</title>
    <link
        rel="website icon"
        type="png"
        href="images/banner/Logo.png"
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
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div class="Navigation_Sticky">
        <nav class="navbar navbar-expand-lg sticky-top">
            <!-- Logo Begin-->
            <div class="flex-container row col-lg-8 col-md-12 col-sm-12">
                <div class="header-right col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2 w-25">
                    <a href="index.php" class="brand"><img src="images/banner/Logo.png" alt="Logo" title="logo" /></a>
                </div>
                <!-- Menu Begin-->
                <div class="container menu-container col-lg-7 col-md-9 col-sm-9 col-9 text-center justify-content-end m-0 py-10">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a href="about.php" class="nav-link">Giới Thiệu </a>
                            </li>
                            <li class="nav-item">
                                <?php foreach ($categoryBrands as $categoryName => $brands): ?>
                                    <div class="dropdown">
                                        <a href="#" class="nav-link dropdown-toggle">
                                            <?php echo htmlspecialchars($categoryName); ?>
                                        </a>
                                        <div class="dropdown-menu">
                                            <?php foreach ($brands as $brand): ?>
                                                <a href="chitietBrand.php?category=<?php echo urlencode($categoryName); ?>&brand=<?php echo urlencode($brand); ?>" class="dropdown-item"><?php echo htmlspecialchars($brand); ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </li>
                            <li class="nav-item">
                                <a href="contact.php" class="nav-link">Liên Hệ</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Menu End -->
            </div>
            <!-- Logo End -->

            <!-- Thanh tìm kiếm (Begin)-->
            <div class="col-lg-3 col-md-12 col-12 justify-content-end" id="search-header">
                <div class="col-lg-12 col-md-12 col-12 justify-content d-flex align-items-center" id="account-cart-container">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Khi đã đăng nhập -->
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle">
                                <i class="fa fa-user" aria-hidden="true"></i> Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <div class="dropdown-menu">
                                <a href="thongtin.php" class="dropdown-item">Thông tin tài khoản</a>
                                <a href="dangxuat.php" class="dropdown-item">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Khi chưa đăng nhập -->
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle">
                                <i class="fa fa-user" aria-hidden="true"></i> Đăng nhập
                            </a>
                            <div class="dropdown-menu">
                                <a href="dangnhap.php" class="dropdown-item">Đăng nhập</a>
                                <a href="dangky.php" class="dropdown-item">Đăng ký</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="dropdown">
                        <a href="giohang.php" class="nav-link">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="badge badge-danger" id="cart-count"><?= getCartTotalItems() ?></span>
                        </a>
                        <div class="dropdown-menu">
                            <?php $cartItems = getCartItems(); ?>
                            <?php if (!empty($cartItems)): ?>
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="item">
                                        <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                                        <p>Giá: <?= number_format($item['price']) ?> VND</p>
                                        <p>Số lượng: <?= $item['quantity'] ?></p>
                                    </div>
                                <?php endforeach; ?>
                                <div class="total">
                                    Tổng cộng: <?= number_format(getCartTotalPrice()) ?> VND
                                </div>
                            <?php else: ?>
                                <div class="empty">Chưa có sản phẩm nào trong giỏ hàng</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-12 justify-content-end " id="account-cart-container">
                    <form class="input-group">
                        <div class="search-bar position-relative">
                            <input type="text" class="form-control search-input" placeholder="Tìm kiếm" required="">
                            <button type="submit" class="btn search-button">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Thanh tìm kiếm (End)-->
        </nav>