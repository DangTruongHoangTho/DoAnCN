<?php include "layout/header.php";
include "layout/banner.php";
$categoryName = $_GET['category'] ?? '';
$brandName = $_GET['brand'] ?? '';
$productsPerPage = 2;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $productsPerPage;

if ($categoryName || $brandName) {
  $sql = "SELECT products.id AS product_id, 
          products.name AS product_name, brands.name AS brand_name, 
          categories.name AS category_name,products.price, MIN(products_imgs.images) AS images FROM products 
          INNER JOIN brands ON products.brand_id = brands.id 
          INNER JOIN categories ON brands.category_id = categories.id 
          INNER JOIN products_imgs ON products.id = products_imgs.product_id
          WHERE categories.name = :categoryName AND brands.name = :brandName
          GROUP BY products.id LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
  $stmt->bindParam(':brandName', $brandName, PDO::PARAM_STR);
  $stmt->bindParam(':limit', $productsPerPage, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$sqlCount = "SELECT COUNT(*) FROM products 
             INNER JOIN brands ON products.brand_id = brands.id 
             INNER JOIN categories ON brands.category_id = categories.id 
             WHERE categories.name = :categoryName AND brands.name = :brandName";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
$stmtCount->bindParam(':brandName', $brandName, PDO::PARAM_STR);
$stmtCount->execute();
$totalProducts = $stmtCount->fetchColumn();

$totalPages = ceil($totalProducts / $productsPerPage);

?>
<!-- Content Begin -->
<main class="main">
  <div class="justify-content-center text-center">
    <!-- Burberry -->
    <section class="block-products" id="burberry">
      <div class="container">
        <h1
          class="category-heading pt-4"
          style="font-family: 'Times New Roman', Times, serif">
          <?php echo htmlspecialchars($brandName); ?>
        </h1>
      </div>
      <div class="container p-row">
        <div class="row">
          <div class="carousel-track col-lg-6 col-sm-4 col-md-4 col-xs-6">
            <?php if (!empty($products)): ?>
              <?php foreach ($products as $product): ?>
                <div class="p-item">
                  <?php
                  $productSlug = removeAccents($product['product_name']);
                  echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>";
                  $imagePath = getImagePath($product['category_name'], $product['brand_name'], $product['images']);
                  ?>
                    <img
                      src="<?php echo $imagePath; ?>"
                      alt=""
                      class="w-50" />
                  <?php echo "</a>";
                   ?>
                  <div class="pro-vendor"><strong><?php echo htmlspecialchars($product['brand_name']); ?></strong></div>
                  <h6 class="pro-name">
                    <?php
                    echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>{$product['product_name']}</a>";
                    ?>
                  </h6>
                  <div class="box-pro-prices">
                    <p class="pro-price"><strong><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</strong></p>
                  </div>
                </div>
                <div class="box-pro-detail">
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Không có sản phẩm nào</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- Phân trang -->
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="chitietBrand.php?category=<?php echo urlencode($categoryName); ?>&brand=<?php echo urlencode($brandName); ?>&page=<?php echo $page - 1; ?>"><</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="chitietBrand.php?category=<?php echo urlencode($categoryName); ?>&brand=<?php echo urlencode($brandName); ?>&page=<?php echo $i; ?>"
            class="<?php echo $i == $page ? 'active disabled' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a href="chitietBrand.php?category=<?php echo urlencode($categoryName); ?>&brand=<?php echo urlencode($brandName); ?>&page=<?php echo $page + 1; ?>">></a>
        <?php endif; ?>
      </div>

    </section>
  </div>
</main>

<!-- Content End -->
<?php include "layout/footer.php"; ?>