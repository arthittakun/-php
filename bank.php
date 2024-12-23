<?php
include "components/navbar.php";
include "model/dbconnect.php";
$sum = isset($_GET['sum']) ? $_GET['sum'] : null;
?>
 <style>
        .container {
            border: 1px solid #000;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        .bank-selection {
            text-align: left;
            margin-bottom: 20px;
        }
        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            text-align: left;
        }
        .buttons {
            margin-top: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            margin: 5px;
        }
    </style>
   <div class="container">
    <h2>ชำระเงิน : โอนเงิน</h2>

    <div class="bank-selection">
        <h4>เลือกธนาคาร</h4>
        <label>
            <input type="radio" name="bank" value="ธนาคารกรุงไทย" data-account="123-456-7890" onclick="updateBankDetails()"> ธนาคารกรุงไทย
        </label><br>
        <label>
            <input type="radio" name="bank" value="ธนาคารกสิกร" data-account="987-654-3210" onclick="updateBankDetails()"> ธนาคารกสิกร
        </label><br>
        <label>
            <input type="radio" name="bank" value="ธนาคารไทยพาณิชย์" data-account="555-444-3333" onclick="updateBankDetails()"> ธนาคารไทยพาณิชย์
        </label>
    </div>

    <div class="info-box">
        <h4>ชำระเงินเข้าบัญชี</h4>
        <p id="selected-bank">ธนาคาร: -</p>
        <p id="account-number">เลขที่บัญชี: -</p>
    </div>

    <p id="current-date">วันที่ซื้อ : -</p>
    <p>รายการรวม : <span id="total-amount"><?= $sum ?></span> บาท</p>

    <label for="slip">หลักฐานการโอน</label>
    <input type="file" id="slip" accept="image/*">

    <div class="buttons">
        <button onclick="submitPayment()">ยืนยันการชำระเงิน</button>
        <button onclick="cancelPayment()">ยกเลิก</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Show the current date
    const currentDate = new Date().toLocaleDateString('th-TH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
    document.getElementById('current-date').textContent = `วันที่ซื้อ : ${currentDate}`;
});

function updateBankDetails() {
    const selectedBank = document.querySelector('input[name="bank"]:checked');
    if (selectedBank) {
        document.getElementById('selected-bank').innerText = `ธนาคาร: ${selectedBank.value}`;
        document.getElementById('account-number').innerText = `เลขที่บัญชี: ${selectedBank.dataset.account}`;
    }
}

function submitPayment() {
    const selectedBank = document.querySelector('input[name="bank"]:checked');
    const slipFile = document.getElementById('slip').files[0];
    const totalAmount = document.getElementById('total-amount').innerText;
    const address = new URLSearchParams(window.location.search).get('address');
    const phone = new URLSearchParams(window.location.search).get('phone');
    const productIds = new URLSearchParams(window.location.search).get('product_ids');

    if (!selectedBank || !slipFile) {
        alert('กรุณาเลือกธนาคารและอัปโหลดหลักฐานการโอน');
        return;
    }

    const formData = new FormData();
    formData.append('bank', selectedBank.value);
    formData.append('account', selectedBank.dataset.account);
    formData.append('totalAmount', totalAmount);
    formData.append('address', address);
    formData.append('phone', phone);
    formData.append('product_ids', productIds);
    formData.append('slip', slipFile);

    fetch('model/payment_bank.php', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert('ชำระเงินสำเร็จ');
                location.href = 'receipt.php';
            } else {
                alert('เกิดข้อผิดพลาด: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function cancelPayment() {
    location.href = "index.php";
}
</script>

      <?php
include "components/foolter.php";
?>