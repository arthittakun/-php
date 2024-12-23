<?php
include "components/navbar.php";
include "model/dbconnect.php";

?>
  <style>
    .container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #f55;
    }

    .section {
        margin-bottom: 20px;
    }

    .section h3 {
        color: #d44;
        margin-bottom: 10px;
    }

    .bank-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .bank-card {
        flex: 1 1 calc(50% - 20px);
        background: #fff0f0;
        border: 1px solid #fdd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .bank-card h4 {
        margin: 10px 0;
        color: #f55;
    }

    .bank-card p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .steps {
        list-style: decimal;
        margin-left: 20px;
        font-size: 14px;
        color: #333;
    }

    .steps li {
        margin-bottom: 10px;
    }

    .footer-note {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #777;
    }
  </style>

  <div class="container">
    <h1>วิธีการชำระเงิน</h1>

    <!-- ส่วนช่องทางการชำระเงิน -->
    <div class="section">
      <h3>ช่องทางการชำระเงิน</h3>
      <div class="bank-details">
        <div class="bank-card">
          <h4>ธนาคารกรุงไทย</h4>
          <p>ชื่อบัญชี: บริษัท ตัวอย่าง จำกัด</p>
          <p>เลขที่บัญชี: 123-456-7890</p>
        </div>
        <div class="bank-card">
          <h4>ธนาคารกสิกร</h4>
          <p>ชื่อบัญชี: บริษัท ตัวอย่าง จำกัด</p>
          <p>เลขที่บัญชี: 987-654-3210</p>
        </div>
        <div class="bank-card">
          <h4>ธนาคารไทยพาณิชย์</h4>
          <p>ชื่อบัญชี: บริษัท ตัวอย่าง จำกัด</p>
          <p>เลขที่บัญชี: 555-444-3333</p>
        </div>
      </div>
    </div>

    <!-- ส่วนขั้นตอนการชำระเงิน -->
    <div class="section">
      <h3>ขั้นตอนการชำระเงิน</h3>
      <ol class="steps">
        <li>เลือกธนาคารที่ต้องการโอนเงิน.</li>
        <li>โอนเงินเข้าบัญชีที่ระบุในตารางด้านบน.</li>
        <li>เก็บหลักฐานการโอนเงิน เช่น สลิปธนาคารหรือหน้าจอการโอน.</li>
        <li>แจ้งชำระเงินผ่านเว็บไซต์หรือช่องทางที่กำหนด.</li>
        <li>รอการยืนยันจากระบบ.</li>
      </ol>
    </div>

    <!-- หมายเหตุเพิ่มเติม -->
    <p class="footer-note">
      หากมีข้อสงสัยเกี่ยวกับวิธีการชำระเงิน กรุณาติดต่อฝ่ายบริการลูกค้า.
    </p>
  </div>

  <?php
include "components/foolter.php";
?>