<?php
include "dbconnect.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $data['orderId'] ?? null;
    $status = $data['status'] ?? null;

    if (!$orderId || $status === null) {
        echo json_encode(['success' => false, 'message' => 'Incomplete data']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE order_detail SET status = :status WHERE order_detail_id = :orderId");
        $stmt->execute([':status' => $status, ':orderId' => $orderId]);

        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
