<?php
include "dbconnect.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $data['userId'] ?? null;
    $name = $data['name'] ?? null;
    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $telephone = $data['telephone'] ?? null;
    $role = $data['role'] ?? null;

    if (!$userId || !$name || !$username || !$email || !$telephone || !$role) {
        echo json_encode(['success' => false, 'message' => 'Incomplete data']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE users 
            SET Name = :name, username = :username, email = :email, telephone = :telephone, role = :role 
            WHERE users_id = :userId
        ");
        $stmt->execute([
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':telephone' => $telephone,
            ':role' => $role,
            ':userId' => $userId
        ]);

        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
