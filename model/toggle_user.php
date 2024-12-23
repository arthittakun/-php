<?php
include "dbconnect.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $data['userId'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE users_id = :userId");
        $stmt->execute([':userId' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'User not found']);
            exit;
        }

        $newRole = $user['role'] === 'admin' ? 'user' : 'admin';

        $updateStmt = $pdo->prepare("UPDATE users SET role = :newRole WHERE users_id = :userId");
        $updateStmt->execute([':newRole' => $newRole, ':userId' => $userId]);

        echo json_encode(['success' => true, 'message' => 'Role toggled successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
