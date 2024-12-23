<?php
session_start();
include "dbconnect.php";

$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($action === "register") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; 

    // ตรวจสอบว่า username ซ้ำหรือไม่
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["success" => false, "message" => "Username นี้มีอยู่ในระบบแล้ว"]);
        exit;
    }

    // บันทึกข้อมูลลงในฐานข้อมูล
    $stmt = $pdo->prepare("INSERT INTO users (username, password, Name, sex, email, telephone) VALUES (?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$username, $password, $firstName . " " . $lastName, $gender, $email, $phone]);
    if ($success) {
        echo json_encode(["success" => true, "message" => "สมัครสมาชิกสำเร็จ"]);
    } else {
        echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการสมัครสมาชิก"]);
    }
    exit;
}

if ($action === "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) { // ตรวจสอบว่าพบข้อมูลผู้ใช้หรือไม่
        if ($user['password'] === $password) { // ตรวจสอบรหัสผ่าน (ข้อความธรรมดา)
            // ตั้งค่าข้อมูล Session
            $_SESSION['user_id'] = $user['users_id'];
            $_SESSION['username'] = $user['username'];
            if($user['role'] === "admin"){
                $_SESSION['admin'] = $user['role'];
            }
            $_SESSION['name'] = $user['Name'];
            echo json_encode(["success" => true, "message" => "เข้าสู่ระบบสำเร็จ"]);
        } else {
            echo json_encode(["success" => false, "message" => "รหัสผ่านไม่ถูกต้อง"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ไม่พบ Username"]);
    }
    exit;
}

// หาก action ไม่ตรง
echo json_encode(["success" => false, "message" => "คำขอไม่ถูกต้อง"]);
exit;
?>
