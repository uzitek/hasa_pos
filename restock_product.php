<?php
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || get_user_role($_SESSION['user_id']) != ROLE_ADMIN) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = (int)$_POST['product_id'];
    $restock_quantity = (int)$_POST['restock_quantity'];

    if ($product_id > 0 && $restock_quantity > 0) {
        $result = restock_product($product_id, $restock_quantity);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error restocking product']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
    }
}

function restock_product($product_id, $quantity) {
    $conn = db_connect();
    $conn->begin_transaction();

    try {
        // Update product quantity
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity + ?, last_stocked_date = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        // Log the restock action
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO inventory_log (product_id, user_id, action, quantity, reason) VALUES (?, ?, 'restock', ?, 'Manual restock')");
        $stmt->bind_param("iii", $product_id, $user_id, $quantity);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}