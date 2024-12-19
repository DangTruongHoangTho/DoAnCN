    <?php
    session_start();
    require 'database/connect.php';
    include "database/function.php";
    if (!isset($_SESSION['user_id'])) {
        header("Location: user_account/dangnhap.php");
        exit();
    }
    $sqlCate = "SELECT categories.name AS category_name, 
                brands.name AS brand_name FROM brands INNER JOIN categories
                ON brands.category_id = categories.id
                ORDER BY categories.name, brands.name";
    $stmtCate = $conn->prepare($sqlCate);
    $stmtCate->execute();
    $categories = $stmtCate->fetchAll(PDO::FETCH_ASSOC);
    $categoryBrands = [];

    if (isset($_SESSION['user_id'])) {
        $total = 0;
        $user_id = $_SESSION['user_id'];
        $sqlUser = "SELECT first_name, last_name, phone, email FROM users WHERE id = :id";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->execute(['id' => $user_id]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $sqlCart = "SELECT c.id AS cart_id, p.id AS product_id, p.name AS product_name, p.quantity, categories.name AS category_name, 
                    b.name AS brand_name, c.quantity, p.price, p.discounted_price, MIN(pi.images) AS first_image FROM carts c
                    INNER JOIN products p ON c.product_id = p.id
                    INNER JOIN brands b ON p.brand_id = b.id    
                    INNER JOIN products_imgs pi ON p.id = pi.product_id 
                    INNER JOIN categories ON b.category_id = categories.id 
                    WHERE c.user_id = ? GROUP BY p.id";
        $stmtCart = $conn->prepare($sqlCart);
        $stmtCart->execute([$user_id]);
        $cartItems = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

        foreach($cartItems as $item){
            $totalPro = $item['discounted_price'] * $item['quantity'];
            $total += $totalPro;
        }
    }


    foreach ($categories as $category) {
        $categoryBrands[$category['category_name']][] = $category['brand_name'];
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Giỏi hàng của bạn</title>
        <link
            rel="website icon"
            type="png"
            href="images/banner/LogoT&T_2.png"
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
        <style>
            .container-cart {
                width: 80%;
                margin: 20px auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Sản phẩm */
            .cart-item {
                align-items: center;
                justify-content: space-between;
                padding: 15px 0;
                border-bottom: 1px solid #ddd;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            .product-image {
                width: 80px;
                height: auto;
                border: 1px solid #ddd;
            }

            .product-details h2 {
                margin: 0;
                font-size: 18px;
                color: #333;
            }

            .product-details p {
                margin: 4px 0;
                font-size: 14px;
            }

            .product-price s {
                color: #aaa;
                font-size: 14px;
            }

            .discount-price {
                color: red;
                font-weight: bold;
                font-size: 16px;
            }

            /* Số lượng */
            .product__details__quantity .quantity .pro-qty {
                align-items: center;
                gap: 5px;
            }

            /* Xóa sản phẩm */
            .remove-item {
                font-size: 17px;
                color: #999;
                cursor: pointer;
            }

            /* Tổng kết */
            .cart-summary {
                margin-top: 20px;
                text-align: right;
                font-size: 16px;
            }

            .cart-summary p {
                margin: 8px 0;
            }

            .cart-summary .input-code {
                color: #007bff;
                cursor: pointer;
            }

            .cart-summary .totals p span {
                font-weight: bold;
            }

            .total-price {
                font-size: 18px;
                color: red;
                font-weight: bold;
            }

            .small-text {
                font-size: 0.9rem;
            }

            .small-heading {
                font-size: 1rem;
            }
            .btn:hover {
                color: #fff;
            }
        </style>
    </head>

    <body>
        <div class="Navigation_Sticky">
            <nav class="navbar navbar-expand-lg sticky-top">
                <!-- Logo Begin-->
                <div class="flex-container row col-lg-8 col-md-12 col-sm-12">
                    <div class="header-right col-xl-2 col-lg-3 col-md-3 col-sm-3 col-3 w-25">
                        <a href="index.php" class="brand"><img src="images/banner/LogoT&T.png" alt="Logo" title="logo" /></a>
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
                <div class="col-lg-4 col-md-12 col-12 justify-content-end" id="search-header">
                    <div class="col-lg-12 col-md-12 col-12 justify-content d-flex align-items-center" id="account-cart-container">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Khi đã đăng nhập -->
                            <div class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle">
                                    <i class="fa fa-user" aria-hidden="true"></i> Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="thongtin.php" class="dropdown-item">Thông tin tài khoản</a>
                                    <a href="user_account/dangxuat.php" class="dropdown-item">Đăng xuất</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Khi chưa đăng nhập -->
                            <div class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle">
                                    <i class="fa fa-user" aria-hidden="true"></i> Đăng nhập
                                </a>
                                <div class="dropdown-menu">
                                    <a href="user_account/dangnhap.php" class="dropdown-item">Đăng nhập</a>
                                    <a href="user_account/dangky.php" class="dropdown-item">Đăng ký</a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="giohang.php" class="nav-link">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="badge badge-danger" id="cart-count"><?= getCartTotalItems() ?></span>
                        </a>
                    </div>

                    <div class="col-lg-12 col-md-12 col-12 justify-content-end " id="account-cart-container">
                        <form class="input-group">
                            <div class="search-bar position-relative">
                                <input type="text" class="form-control search-input" placeholder="Tìm kiếm  " required="">
                                <button type="submit" class="btn search-button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Thanh tìm kiếm (End)-->
            </nav>