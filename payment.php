<?php
include "components/navbar.php";
include "model/dbconnect.php";

$productIds = isset($_GET['id']) ? explode(',', $_GET['id']) : [];

if (empty($productIds)) {
    echo "<p class='text-center'>ไม่มีสินค้าในรายการ</p>";
    exit;
}

$placeholders = rtrim(str_repeat('?,', count($productIds)), ',');
$stmt = $pdo->prepare("SELECT * FROM product WHERE product_id IN ($placeholders)");
$stmt->execute($productIds);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productIdsString = implode(',', array_column($products, 'product_id'));
?>

<style>
   .product-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.product-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    border: 1px solid #fdd;
    padding: 10px;
    border-radius: 5px;
    background-color: #fff5f5;
}

.product-item img {
    width: 80px;
    height: auto;
    margin-right: 15px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.sum-price {
    font-size: 18px;
    font-weight: bold;
    color: #f55;
    text-align: right;
    margin-top: 20px;
}

.btn-success {
    background-color: #f55;
    border: none;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.btn-success:hover {
    background-color: #d44;
}

.form-control:focus {
    border-color: #f55;
    box-shadow: 0 0 5px rgba(243, 94, 94, 0.5);
}

</style>
<div class="container my-5">
    <h2 class="mb-4">ชำระเงิน</h2>

    <h4>สินค้าที่สั่งซื้อ</h4>
    <ul class="product-list">
        <?php foreach ($products as $product): ?>
            <li class="product-item">
                <div>
                    <img src="<?= htmlspecialchars($product['product_img']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                    <span><?= htmlspecialchars($product['product_name']) ?></span>
                </div>
                <span><?= htmlspecialchars($product['product_price']) ?> บาท</span>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="sum-price">
        ราคารวม: 
        <span id="sumPrice">
            <?= array_sum(array_column($products, 'product_price')) ?>
        </span> บาท
    </div>

    <h4 class="mt-4">กรอกที่อยู่จัดส่ง</h4>
    <form id="checkoutForm">
        <div class="form-group">
            <label for="addresslabel">ที่อยู่</label>
            <textarea id="address1" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="phonelabel">เบอร์โทร</label>
            <input type="text" id="phone1" class="form-control" required>
        </div>

        <h4 class="mt-4">เลือกวิธีการชำระเงิน</h4>
        <div class="form-group">
            <div>
                <input type="radio" id="cash" name="paymentMethod" value="เงินสด" required>
                <label for="cash">ชำระด้วยเงินสด</label>
            </div>
            <div>
                <input type="radio" id="bank" name="paymentMethod" value="ธนาคาร" required>
                <label for="bank">ชำระด้วยธนาคาร</label>
            </div>
        </div>

        <div class="form-group">
            <label for="currentDate">วันที่:</label>
            <input type="text" id="currentDate" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">ยืนยัน</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sumPriceSpan = document.getElementById('sumPrice');
    const checkoutForm = document.getElementById('checkoutForm');
    const currentDateInput = document.getElementById('currentDate');

    function setCurrentDate() {
        const today = new Date();
        const formattedDate = today.toLocaleDateString('th-TH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
        currentDateInput.value = formattedDate;
    }

    checkoutForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const address = document.getElementById('address1').value;
        const phone = document.getElementById('phone1').value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;

        if (address === '' || phone === '') {
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }

        const totalAmount = parseFloat(sumPriceSpan.textContent);

        if (paymentMethod === "เงินสด") {
            fetch('model/payment_success.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    address: address,
                    phone: phone,
                    paymentMethod: paymentMethod,
                    products: <?= json_encode($products) ?>,
                    totalAmount: totalAmount,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert('การสั่งซื้อสำเร็จ!');
                        window.location.href = 'receipt.php';
                    } else {
                        alert(`เกิดข้อผิดพลาด: ${data.message}`);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
                });
        } else {
            location.href = "bank.php?sum=" + encodeURIComponent(sumPriceSpan.textContent)
                + "&address=" + encodeURIComponent(address)
                + "&phone=" + encodeURIComponent(phone)
                + "&product_ids=" + encodeURIComponent("<?= $productIdsString ?>");
        }
    });

    setCurrentDate();
});

</script>
<?php
include "components/foolter.php";
?>