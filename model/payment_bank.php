<?php
include "dbconnect.php"; // ใช้การเชื่อมต่อ PDO
session_start(); // Ensure session is started

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีไฟล์และข้อมูลครบถ้วน
    if (
        empty($_POST['bank']) || 
        empty($_POST['account']) || 
        empty($_POST['totalAmount']) || 
        empty($_POST['address']) || 
        empty($_POST['phone']) || 
        empty($_POST['product_ids']) || 
        empty($_FILES['slip'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Incomplete data']);
        exit;
    }

    $bank = $_POST['bank'];
    $account = $_POST['account'];
    $totalAmount = $_POST['totalAmount'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $productIds = explode(',', $_POST['product_ids']);
    $slip = $_FILES['slip'];
    $users_id = 0;

    // ตรวจสอบ session name
    if (!isset($_SESSION['name'])) {
        echo json_encode(['success' => false, 'message' => 'User session not set']);
        exit;
    }

    $name = $_SESSION['name'];
    $stmt = $pdo->prepare("SELECT users_id FROM users WHERE Name = :name");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $users_id = $result['users_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found for the provided session']);
        exit;
    }

    // ตรวจสอบไฟล์หลักฐานการโอน
    if ($slip['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error']);
        exit;
    }

    // อัปโหลดไฟล์
    $uploadDir = '../assets/img/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }
    $fileName = uniqid() . '_' . basename($slip['name']);
    $uploadFile = $uploadDir . $fileName;
    $upfile = "assets/img/" . $fileName;

    if (!move_uploaded_file($slip['tmp_name'], $uploadFile)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        exit;
    }

    try {
        // เริ่ม Transaction
        $pdo->beginTransaction();

        foreach ($productIds as $productId) {
            $stmt = $pdo->prepare("
                INSERT INTO order_detail (users_id, product_id, address, phone, payment_method, slip_path) 
                VALUES (:users_id, :product_id, :address, :phone, :payment_method, :slip_path)
            ");
            $stmt->execute([
                ':users_id' => $users_id,
                ':product_id' => $productId,
                ':address' => $address,
                ':phone' => $phone,
                ':payment_method' => $bank,
                ':slip_path' => $upfile,
            ]);
        }

        // Commit Transaction
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Payment recorded successfully']);
    } catch (PDOException $e) {
        // Rollback Transaction ในกรณีเกิดข้อผิดพลาด
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
