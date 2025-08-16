<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if admin is logged in
    if (!is_admin_logged_in()) {
        $_SESSION['error'] = "You must be logged in to process requests.";
        header("Location: ../admin/login.html");
        exit();
    }

    $request_id = (int)$_POST['request_id'];
    $status = sanitize_input($_POST['status']);
    $units_provided = isset($_POST['units_provided']) ? (int)$_POST['units_provided'] : 0;
    $notes = sanitize_input($_POST['notes']);

    // Get request details
    $request_stmt = $conn->prepare("SELECT blood_group, units_required FROM requests WHERE id = ?");
    $request_stmt->bind_param("i", $request_id);
    $request_stmt->execute();
    $request = $request_stmt->get_result()->fetch_assoc();
    $request_stmt->close();

    // Update request status
    $stmt = $conn->prepare("UPDATE requests SET status = ?, notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $notes, $request_id);
    
    if ($stmt->execute()) {
        // Update inventory if request was fulfilled
        if ($status === 'Fulfilled' || $status === 'Partially Fulfilled') {
            $inventory_stmt = $conn->prepare("UPDATE blood_inventory SET units_available = units_available - ? WHERE blood_group = ?");
            $inventory_stmt->bind_param("is", $units_provided, $request['blood_group']);
            $inventory_stmt->execute();
            $inventory_stmt->close();
        }
        
        $_SESSION['success'] = "Request updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update request. Please try again.";
    }
    
    $stmt->close();
    header("Location: ../admin/requests.php");
    exit();
} else {
    header("Location: ../admin/requests.php");
    exit();
}
?>