<?php
include "components/navbar.php";
include "model/dbconnect.php";
?>
<style>
     .banner {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: auto;
        overflow: hidden;
    }

    .banner img {
        width: 100%;
        height: 450px;
        display: none;
    }

    .banner img.active {
        display: block;
    }

    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        font-size: 18px;
        padding: 10px 20px;
        cursor: pointer;
        z-index: 10;
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }

    .nav-button:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    .nav-button.prev {
        left: 10px;
    }

    .nav-button.next {
        right: 10px;
    }

    .container {
        margin-top: 20px;
        width: 90%;
        margin: 20px auto;
    }

    .head-product {
        text-align: center;
    }

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
        border: 1px solid #fdd;
        background-color: #fff0f0;
    }

    .card img {
    width: 100%;
    height: 200px; /* ตั้งค่าความสูงคงที่ */
    object-fit: cover; /* ตัดภาพให้พอดีกับกรอบ */
    border-radius: 8px; /* มุมโค้งมน */
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

    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .banner img {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
<div class="banner">
    <button class="nav-button prev" id="prev">❮</button>
    <button class="nav-button next" id="next">❯</button>
</div>
<div class="container">
    <div class="head-product">
        <h3>รายการสินค้า</h3>
    </div>
    <div class="grid-container">
        <?php

        $stmt = $pdo->query("SELECT * FROM product ORDER BY RAND() LIMIT 8");
        while ($row = $stmt->fetch()) {
            ?>
            <div class="grid-item">
                <div class="card">
                    <img src="<?= $row['product_img'] ?>" class="card-img-top" alt="Sample Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['product_name'] ?></h5>
                        <p class="card-text">ราคา <?= $row['product_price'] ?></p>
                       <?php if(isset($_SESSION['username'])){ ?>
                        <a href="payment.php?id=<?= $row['product_id'] ?>" class="btn btn-primary" >สั่งซื้อสินค้า</a>
                        <?php }else{ ?>
                        <a href="" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-primary" >สั่งซื้อสินค้า</a>
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
    const imageArray = [
        'assets/img/banner.jpg',
        'assets/img/banner2.jpg',
        'assets/img/banner3.jpg'
    ];

    const bannerDiv = document.querySelector('.banner');
    let currentIndex = 0;

    imageArray.forEach((imageSrc, index) => {
        const imgElement = document.createElement('img');
        imgElement.src = imageSrc;
        imgElement.alt = `Image ${index + 1}`;
        if (index === 0) {
            imgElement.classList.add('active');
        }
        bannerDiv.appendChild(imgElement);
    });

    const images = document.querySelectorAll('.banner img');

    function showNextImage() {
        images[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % images.length;
        images[currentIndex].classList.add('active');
    }

    function showPreviousImage() {
        images[currentIndex].classList.remove('active');
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        images[currentIndex].classList.add('active');
    }

    document.getElementById('next').addEventListener('click', showNextImage);
    document.getElementById('prev').addEventListener('click', showPreviousImage);
    setInterval(showNextImage, 3000);


    

</script>
<?php
include "components/foolter.php";
?>