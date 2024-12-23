<?php
session_start();
include "dbconnect.php"; // PDO connection

// Decode incoming JSON
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($data['address']) || empty($data['phone']) || empty($data['paymentMethod']) || empty($data['products'])) {
        echo json_encode(['success' => false, 'message' => 'Incomplete data. Please provide address, phone, paymentMethod, and products.']);
        exit;
    }

    $address = $data['address'];
    $phone = $data['phone'];
    $paymentMethod = $data['paymentMethod'];
    $products = $data['products'];
    $users_id = null;

    // Validate session
    if (isset($_SESSION['name'])) {
        $name = $_SESSION['name'];
        $stmt = $pdo->prepare("SELECT users_id FROM users WHERE Name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $users_id = $result['users_id'];
        } else {
            echo json_encode(['success' => false, 'message' => 'No user found for the current session.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User session not set.']);
        exit;
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Insert each product into the order_detail table
        foreach ($products as $product) {
            if (empty($product['product_id'])) {
                throw new Exception('Invalid product data.');
            }

            $stmt = $pdo->prepare("
                INSERT INTO order_detail (users_id, product_id, address, phone, payment_method) 
                VALUES (:users_id, :product_id, :address, :phone, :payment_method)
            ");
            $stmt->execute([
                ':users_id' => $users_id,
                ':product_id' => $product['product_id'],
                ':address' => $address,
                ':phone' => $phone,
                ':payment_method' => $paymentMethod,
            ]);
        }

        // Commit transaction
        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Order saved successfully']);
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
