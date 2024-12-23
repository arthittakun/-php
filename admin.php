<?php
include "components/navbar.php";
include "model/dbconnect.php";
?>
<style>
  .top {
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.btn-primary {
    background-color: #f55;
    border: none;
    color: white;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #d44;
}

.btn-secondary {
    background-color: white;
    color: #f55;
    border: 2px solid #f55;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #fdd;
    color: #d44;
}

.list-group-item {
    border: 1px solid #fdd;
    background-color: #fff5f5;
    border-radius: 8px;
    margin-bottom: 10px;
    padding: 15px;
}

</style>
<div class="top d-flex gap-2">
    <a href="admin.php?id=1" class="btn btn-primary">ข้อมูลการสั่งซื้อ</a>
    <a href="admin.php?id=2" class="btn btn-secondary">ข้อมูลลูกค้าสมาชิก</a>
</div>
<div class="container">
    <?php
    if (isset($_GET['id']) && $_GET['id'] == 1) {
        $stmt = $pdo->query("SELECT * FROM order_detail");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-5">
    <h2 class="mb-4">รายการคำสั่งซื้อ</h2>
    <ul class="list-group">
        <?php foreach ($orders as $order): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>คำสั่งซื้อ #<?= $order['order_detail_id'] ?></strong>
                    <br>ผู้ใช้: <?= $order['users_id'] ?>
                    <br>สถานะ:
                    <?= $order['status'] === '0' ? 'รอดำเนินการ' : ($order['status'] === '1' ? 'ชำระเงินแล้ว' : 'ปฏิเสธ') ?>
                </div>
                <div>
                    <img src="<?= htmlspecialchars($order['slip_path']) ?>" alt="Slip" class="img-thumbnail"
                        style="width: 100px; height: auto;">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal"
                        onclick="viewDetails(<?= htmlspecialchars(json_encode($order)) ?>)">ดูรายละเอียด</button>
                    <button class="btn btn-success btn-sm"
                        onclick="updateStatus(<?= $order['order_detail_id'] ?>, 1)">ยืนยัน</button>
                    <button class="btn btn-danger btn-sm"
                        onclick="updateStatus(<?= $order['order_detail_id'] ?>, -1)">ปฏิเสธ</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Modal แสดงรายละเอียด -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">รายละเอียดคำสั่งซื้อ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>คำสั่งซื้อ ID:</strong> <span id="orderId"></span></p>
                <p><strong>ผู้ใช้ ID:</strong> <span id="userId"></span></p>
                <p><strong>สินค้า ID:</strong> <span id="productId2"></span></p>
                <p><strong>ที่อยู่:</strong> <span id="address2"></span></p>
                <p><strong>เบอร์โทร:</strong> <span id="phone2"></span></p>
                <p><strong>วิธีชำระเงิน:</strong> <span id="paymentMethod"></span></p>
                <p><strong>หลักฐานการโอน:</strong></p>
                <img id="slipImage" src="#" alt="Slip Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    // ฟังก์ชันแสดงรายละเอียดใน Modal
    function viewDetails(order) {
        // เติมข้อมูลใน Modal
        console.log(order)
        document.getElementById('orderId').innerText = order.order_detail_id || '-';
        document.getElementById('userId').innerText = order.users_id || '-';
        document.getElementById('productId2').innerText = order.product_id || '-';
        document.getElementById('address2').innerText = order.address || '-';
        document.getElementById('phone2').innerText = order.phone || '-';
        document.getElementById('paymentMethod').innerText = order.payment_method || '-';

        const slipImage = document.getElementById('slipImage');
        slipImage.src = order.slip_path || '#'; // ตรวจสอบว่ามีรูปภาพหรือไม่
        slipImage.alt = `Slip for Order #${order.order_detail_id}`;
    }

    // ฟังก์ชันอัปเดตสถานะคำสั่งซื้อ
    function updateStatus(orderId, status) {
        const confirmation = confirm(status === 1 ? "ยืนยันการชำระเงิน?" : "ปฏิเสธคำสั่งซื้อ?");
        if (!confirmation) return;

        fetch('model/update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderId, status })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("สถานะอัปเดตสำเร็จ");
                    location.reload();
                } else {
                    alert("เกิดข้อผิดพลาด: " + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
    <?php }else{ 
        $stmt = $pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="container my-5">
    <h2 class="mb-4">รายชื่อสมาชิก</h2>
    <ul class="list-group">
        <?php foreach ($users as $user): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?= htmlspecialchars($user['Name']) ?></strong>
                    <br>Username: <?= htmlspecialchars($user['username']) ?>
                    <br>บทบาท: <?= htmlspecialchars($user['role']) ?>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#userModal"
                            onclick="viewUser(<?= htmlspecialchars(json_encode($user)) ?>)">ดูข้อมูล</button>
                    <button class="btn btn-warning btn-sm" onclick="toggleRole(<?= $user['users_id'] ?>)">สลับบทบาท</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['users_id'] ?>)">ลบ</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Modal แสดงรายละเอียด -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">รายละเอียดสมาชิก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ชื่อ:</strong> <span id="userName">-</span></p>
                <p><strong>Username:</strong> <span id="userUsername">-</span></p>
                <p><strong>เพศ:</strong> <span id="userSex">-</span></p>
                <p><strong>Email:</strong> <span id="userEmail">-</span></p>
                <p><strong>เบอร์โทร:</strong> <span id="userTelephone">-</span></p>
                <p><strong>บทบาท:</strong> <span id="userRole">-</span></p>
                <button class="btn btn-success w-100" onclick="openEditModal()">แก้ไขข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal แก้ไข -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">แก้ไขข้อมูลสมาชิก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <div class="modal-body">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">ชื่อ</label>
                        <input type="text" class="form-control" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTelephone" class="form-label">เบอร์โทร</label>
                        <input type="text" class="form-control" id="editTelephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">บทบาท</label>
                        <select class="form-select" id="editRole">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentUser;

    function viewUser(user) {
        currentUser = user;
        document.getElementById('userName').innerText = user.Name || '-';
        document.getElementById('userUsername').innerText = user.username || '-';
        document.getElementById('userSex').innerText = user.sex || '-';
        document.getElementById('userEmail').innerText = user.email || '-';
        document.getElementById('userTelephone').innerText = user.telephone || '-';
        document.getElementById('userRole').innerText = user.role || '-';
    }

    function openEditModal() {
        if (currentUser) {
            document.getElementById('editUserId').value = currentUser.users_id;
            document.getElementById('editName').value = currentUser.Name || '';
            document.getElementById('editUsername').value = currentUser.username || '';
            document.getElementById('editEmail').value = currentUser.email || '';
            document.getElementById('editTelephone').value = currentUser.telephone || '';
            document.getElementById('editRole').value = currentUser.role || 'user';

            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        }
    }

    document.getElementById('editUserForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const userId = document.getElementById('editUserId').value;
        const name = document.getElementById('editName').value;
        const username = document.getElementById('editUsername').value;
        const email = document.getElementById('editEmail').value;
        const telephone = document.getElementById('editTelephone').value;
        const role = document.getElementById('editRole').value;

        fetch('model/edit_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId, name, username, email, telephone, role })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('แก้ไขข้อมูลสำเร็จ');
                    location.reload();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function deleteUser(userId) {
        if (!confirm('คุณต้องการลบสมาชิกนี้หรือไม่?')) return;

        fetch('model/delete_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('ลบสมาชิกสำเร็จ');
                    location.reload();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function toggleRole(userId) {
        fetch('model/toggle_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('สลับบทบาทสำเร็จ');
                    location.reload();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
        <?php } ?>
</div>
<?php
include "components/foolter.php";
?>