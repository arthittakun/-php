<?php
include "../model/dbconnect.php";

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE product_name LIKE :query");
    $stmt->execute(['query' => "%$query%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM product");
}

while ($row = $stmt->fetch()) {
    ?>
    <div class="grid-item">
        <div class="card">
            <img src="<?= htmlspecialchars($row['product_img']) ?>" class="card-img-top" alt="Sample Image">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                <p class="card-text">ราคา <?= htmlspecialchars($row['product_price']) ?></p>
                <a href="#" class="btn btn-primary">สั่งซื้อสินค้า</a>
            </div>
        </div>
    </div>
    <?php
}
?>
