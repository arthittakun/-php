<?php
include "components/navbar.php";
include "model/dbconnect.php";
try {
    $stmt = $pdo->query("SELECT * FROM order_detail ORDER BY order_detail_id DESC LIMIT 1");
    $latestOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($latestOrder) {
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>ใบเสร็จรับเงิน</title>
            <style>
                .receipt {
                    max-width: 400px;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #fdd;
                    border-radius: 10px;
                    font-family: Arial, sans-serif;
                    background-color: #fff5f5;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                .receipt-header {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .receipt-header h2 {
                    color: #f55;
                    margin: 0;
                }

                .receipt-details {
                    margin-bottom: 20px;
                }

                .receipt-details p {
                    margin: 5px 0;
                }

                .receipt-footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 14px;
                }

                .receipt-footer img {
                    max-width: 100%;
                    height: auto;
                    margin-top: 10px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }

                .btn-primary {
                    display: block;
                    text-align: center;
                    background-color: #f55;
                    color: white;
                    padding: 10px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 20px;
                    transition: background-color 0.3s ease;
                }

                .btn-primary:hover {
                    background-color: #d44;
                }
            </style>
        </head>

        <body>
            <div class="receipt">
                <div class="receipt-header">
                    <h2>ใบเสร็จรับเงิน</h2>
                    <p>หมายเลขคำสั่งซื้อ: <?= htmlspecialchars($latestOrder['order_detail_id']) ?></p>
                </div>
                <div class="receipt-details">
                    <p><strong>รหัสผู้ใช้:</strong> <?= htmlspecialchars($latestOrder['users_id']) ?></p>
                    <p><strong>รหัสสินค้า:</strong> <?= htmlspecialchars($latestOrder['product_id']) ?></p>
                    <p><strong>ที่อยู่:</strong> <?= htmlspecialchars($latestOrder['address']) ?></p>
                    <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($latestOrder['phone']) ?></p>
                    <p><strong>วิธีชำระเงิน:</strong> <?= htmlspecialchars($latestOrder['payment_method']) ?></p>
                    <p><strong>วันที่และเวลา:</strong> <?= htmlspecialchars($latestOrder['time']) ?></p>
                </div>
                <?php
                if ($latestOrder['payment_method'] !== "เงินสด") {
                    ?>
                    <div class="receipt-footer">
                        <p><strong>หลักฐานการโอนเงิน:</strong></p>
                        <img src="<?= htmlspecialchars($latestOrder['slip_path']) ?>" alt="หลักฐานการโอน">
                    </div>
                <?php } ?>
            </div>
            <a href="index.php">กลับไปหน้าแรก</a>
        </body>

        </html>
        <?php
    } else {
        echo "<p>ไม่พบคำสั่งซื้อ</p>";
    }
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูลคำสั่งซื้อล่าสุด: " . $e->getMessage();
}
include "components/foolter.php";
?>
