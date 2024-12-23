<?php
include "components/navbar.php";
include "model/dbconnect.php";
?>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 4fr));
        gap: 16px;
        max-width: 100%;
        margin: auto;
        padding: 16px;
    }

    .grid-item {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        width: 100%;
        max-width: 250px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        overflow: hidden;
        background-color: #fff0f0;
        border: 1px solid #fdd;
    }

    .card img {
        width: 100%;
        height: 200px;
        /* ตั้งค่าความสูงคงที่ */
        object-fit: cover;
        /* ตัดภาพให้พอดีกับกรอบ */
        border-radius: 8px;
        /* มุมโค้งมน */
    }

    .btn-primary:hover {
        background-color: #d44;
    }

    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        text-align: center;
        padding: 16px;
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #f55;
    }

    .card-text {
        font-size: 14px;
        color: #555;
        margin-bottom: 16px;
    }

    .btn-primary {
        background-color: #f55;
        color: #fff;
        border: none;
        padding: 10px 20px;
        text-decoration: none;
        font-size: 14px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #d44;
    }

    .container {
        margin-top: 20px;
        width: 90%;
        margin: 20px auto;
    }

    h2 {
        text-align: center;
        color: #f55;
        margin-bottom: 20px;
    }

    #searchInput {
        display: block;
        margin: 0 auto 20px;
        padding: 10px;
        width: 50%;
        border: 2px solid #f55;
        border-radius: 4px;
        outline: none;
        font-size: 16px;
    }

    #searchInput:focus {
        border-color: #d44;
        box-shadow: 0 0 5px rgba(243, 94, 94, 0.5);
    }
</style>
<div class="container">
    <h2>ค้นหาสินค้า</h2>
    <input type="text" id="searchInput" placeholder="พิมพ์ชื่อสินค้า..." onkeyup="searchProduct()">
    <div id="productGrid" class="grid-container">
        <?php
        $stmt = $pdo->query("SELECT * FROM product");
        while ($row = $stmt->fetch()) {
            ?>
            <div class="grid-item">
                <div class="card">
                    <img src="<?= $row['product_img'] ?>" class="card-img-top" alt="Sample Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['product_name'] ?></h5>
                        <p class="card-text">ราคา <?= $row['product_price'] ?></p>
                        <?php if (isset($_SESSION['username'])) { ?>
                            <a href="payment.php?id=<?= $row['product_id'] ?>" class="btn btn-primary">สั่งซื้อสินค้า</a>
                        <?php } else { ?>
                            <a href="" data-bs-toggle="modal" data-bs-target="#loginModal"
                                class="btn btn-primary">สั่งซื้อสินค้า</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
</div>

<script>
    function searchProduct() {
        const query = document.getElementById("searchInput").value;
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("productGrid").innerHTML = xhr.responseText;
            }
        };

        xhr.open("GET", "components/search_product.php?query=" + encodeURIComponent(query), true);
        xhr.send();
    }
</script>

<?php
include "components/foolter.php";
?>