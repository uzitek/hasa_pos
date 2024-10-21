<?php
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = isset($_POST['customer_id']) ? (int)$_POST['customer_id'] : null;
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);

    if ($customer_id) {
        $result = update_customer($customer_id, $name, $email, $phone, $address);
    } else {
        $result = add_customer($name, $email, $phone, $address);
    }

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving customer']);
    }
}

function add_customer($name, $email, $phone, $address) {
    $conn = db_connect();
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    return $stmt->execute();
}

function update_customer($id, $name, $email, $phone, $address) {
    $conn = db_connect();
    $stmt = $conn->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
    return $stmt->execute();
}