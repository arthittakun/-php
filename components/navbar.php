<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>shop</title>
</head>
<style>
    body {
        background-color: #fff;
    font-family: 'Kanit', sans-serif;
    }

    nav {
        background-color: #f55;
    }

    .nav-link.active {
        color: #fff !important;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #f55;
        border-color: #f33;
    }

    .btn-primary:hover {
        background-color: #d44;
        border-color: #c33;
    }

    .modal-header {
        background-color: #f55;
        color: #fff;
    }

    .modal-footer .btn-secondary {
        background-color: #fff;
        color: #f55;
        border-color: #f55;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #f55;
        color: #fff;
    }
</style>

<body>




    <nav class="navbar navbar-expand-lg " style="justify-content: center;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ร้านขายเสื้อผ้า</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- เมนูฝั่งซ้าย -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list-product.php">
                            <i class="fas fa-list"></i> รายการสินค้า
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pay-method.php">
                            <i class="fas fa-credit-card me-2"></i> วิธีชำระเงิน
                        </a>
                    </li>

                </ul>

                <!-- เมนูฝั่งขวา -->
                <ul class="navbar-nav ms-auto">

                    <?php
                    if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {

                        ?>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ms-3 me-5">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                    id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    if (isset($_SESSION['name'])) {
                                        echo $_SESSION['name'];
                                    } else {
                                        echo "No name";
                                    }
                                    ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="navbarDropdown">


                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#receiptModal">
                                            <i class="fas fa-file-invoice me-2"></i> ใบเสร็จคำสั่งซื้อ
                                        </a>
                                    </li>

                                    <?php if (isset($_SESSION['admin'])) { ?>
                                        <li><a href="admin.php" class="nav-link">
                                                <i class="fas fa-cogs me-2"></i> จัดการระบบ
                                            </a>

                                        </li>

                                    <?php } ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php"><i
                                                class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ</a>
                                    </li>



                                </ul>
                            </li>
                        </ul>
                    <?php } else { ?>

                        <li class="nav-item">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class="fas fa-user-plus"></i> สมัครสมาชิก
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ
                            </button>
                        </li>
                    <?php } ?>
                </ul>




            </div>
        </div>
    </nav>

    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">รายการคำสั่งซื้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>รหัสคำสั่งซื้อ</th>
                                <th>ผู้ใช้</th>
                                <th>สถานะ</th>
                                <th>เวลา</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "model/dbconnect.php";

                            $stmt = $pdo->query("SELECT * FROM order_detail ORDER BY order_detail_id DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $statusText = $row['status'] === '0' ? 'รอดำเนินการ' : ($row['status'] === '1' ? 'ชำระเงินแล้ว' : 'ปฏิเสธ');
                                echo "<tr>
                                <td>{$row['order_detail_id']}</td>
                                <td>{$row['users_id']}</td>
                                <td>{$statusText}</td>
                                <td>{$row['time']}</td>
                                <td>
                                    <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#fullReceiptModal' 
                                            onclick='showReceipt({$row['order_detail_id']})'>
                                        ดูใบเสร็จ
                                    </button>
                                </td>
                            </tr>";

                                // Add hidden full receipt data for each order
                                echo "<div id='receipt-{$row['order_detail_id']}' class='receipt-data' style='display: none;'>
                                    <p><strong>รหัสคำสั่งซื้อ:</strong> {$row['order_detail_id']}</p>
                                    <p><strong>ผู้ใช้:</strong> {$row['users_id']}</p>
                                    <p><strong>สินค้า:</strong> {$row['product_id']}</p>
                                    <p><strong>ที่อยู่:</strong> {$row['address']}</p>
                                    <p><strong>เบอร์โทร:</strong> {$row['phone']}</p>
                                    <p><strong>วิธีชำระเงิน:</strong> {$row['payment_method']}</p>
                                    <p><strong>สถานะ:</strong> {$statusText}</p>";
                                if (!empty($row['slip_path']) && file_exists($row['slip_path'])) {
                                    echo "<p><strong>หลักฐานการชำระเงิน:</strong></p>
                                    <img src='{$row['slip_path']}' alt='Slip' style='width:100%; max-width:400px;'>";
                                }
                                echo "</div>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Receipt Modal -->
    <div class="modal fade receipt-modal" id="fullReceiptModal" tabindex="-1" aria-labelledby="fullReceiptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fullReceiptModalLabel">ใบเสร็จคำสั่งซื้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fullReceiptContent">
                    <p class="text-center">กรุณาเลือกใบเสร็จจากรายการ</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal สมัครสมาชิก -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">สมัครสมาชิก</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="firstName" class="form-label">ชื่อลูกค้า</label>
                            <input type="text" class="form-control" id="firstName">
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" id="lastName">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" id="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">ที่อยู่ / รหัสไปรษณีย์</label>
                            <textarea class="form-control" id="address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เพศ</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">หญิง</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                                <label class="form-check-label" for="male">ชาย</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#loginModal">เข้าสู่ระบบ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">เข้าสู่ระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="loginUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="loginUsername">
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#registerModal">สมัครสมาชิก</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function showReceipt(orderId) {
            const fullReceiptContent = document.getElementById('fullReceiptContent');
            const receiptData = document.getElementById(`receipt-${orderId}`);

            if (receiptData) {
                fullReceiptContent.innerHTML = receiptData.innerHTML;
            } else {
                fullReceiptContent.innerHTML = '<p class="text-center text-danger">ไม่พบข้อมูลใบเสร็จ</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cartItemsDiv = document.getElementById('cartItems');
            const totalPriceSpan = document.getElementById('totalPrice');
            const cartModal = document.getElementById('cartModal');

            function renderCart() {
                // ดึงข้อมูลตะกร้าสินค้าจาก localStorage
                const cart = JSON.parse(localStorage.getItem('cart')) || [];

                // ล้างเนื้อหาเดิม
                cartItemsDiv.innerHTML = '';
                let total = 0;

                // ถ้าไม่มีสินค้าในตะกร้า
                if (cart.length === 0) {
                    cartItemsDiv.innerHTML = '<p class="text-center">ตะกร้าสินค้าว่าง</p>';
                    totalPriceSpan.textContent = total;
                    return;
                }

                // แสดงรายการสินค้าในตะกร้า
                cart.forEach(item => {
                    const itemRow = document.createElement('div');
                    itemRow.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'border-bottom', 'py-2');
                    itemRow.innerHTML = `
                <div>
                    <strong>${item.name}</strong>
                    <p class="mb-0">${item.price} บาท</p>
                </div>
                <button class="btn btn-danger btn-sm remove-item" data-id="${item.id}">ลบ</button>
            `;
                    cartItemsDiv.appendChild(itemRow);

                    // คำนวณราคารวม
                    total += parseFloat(item.price);
                });

                // แสดงราคารวม
                totalPriceSpan.textContent = total.toFixed(2);

                // เพิ่มฟังก์ชันลบสินค้า
                const removeButtons = document.querySelectorAll('.remove-item');
                removeButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        const productId = e.target.getAttribute('data-id');
                        removeFromCart(productId);
                    });
                });
            }

            function removeFromCart(productId) {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(item => item.id !== productId);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart(); // อัปเดตการแสดงผล
            }

            // เพิ่ม Event Listener เมื่อ Modal เปิด
            cartModal.addEventListener('show.bs.modal', renderCart);
        });

        // ฟังก์ชันสมัครสมาชิก
        document.querySelector("#registerModal form").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append("action", "register");
            formData.append("firstName", document.getElementById("firstName").value);
            formData.append("lastName", document.getElementById("lastName").value);
            formData.append("phone", document.getElementById("phone").value);
            formData.append("address", document.getElementById("address").value);
            formData.append("gender", document.querySelector('input[name="gender"]:checked').value);
            formData.append("email", document.getElementById("email").value);
            formData.append("username", document.getElementById("username").value);
            formData.append("password", document.getElementById("password").value);

            fetch("./model/user_handler.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("สมัครสมาชิกสำเร็จ!");
                        document.querySelector("#registerModal form").reset();
                    } else {
                        alert("เกิดข้อผิดพลาด: " + data.message);
                    }
                });
        });

        // ฟังก์ชันเข้าสู่ระบบ
        document.querySelector("#loginModal form").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append("action", "login");
            formData.append("username", document.getElementById("loginUsername").value);
            formData.append("password", document.getElementById("loginPassword").value);

            console.log(document.getElementById("loginUsername").value);
            fetch("./model/user_handler.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("เข้าสู่ระบบสำเร็จ!");
                        window.location.reload();
                    } else {
                        alert("เกิดข้อผิดพลาด: " + data.message);
                    }
                });
        });
    </script>