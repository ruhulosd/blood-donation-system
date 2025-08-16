<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if admin is logged in
    if (!is_admin_logged_in()) {
        $_SESSION['error'] = "You must be logged in to process donations.";
        header("Location: ../admin/login.html");
        exit();
    }

    $donor_id = (int)$_POST['donor_id'];
    $donation_date = sanitize_input($_POST['donation_date']);
    $blood_group = sanitize_input($_POST['blood_group']);
    $units_donated = (float)$_POST['units_donated'];
    $health_screening = sanitize_input($_POST['health_screening']);
    $status = sanitize_input($_POST['status']);

    // Insert donation record
    $stmt = $conn->prepare("INSERT INTO donations (donor_id, donation_date, blood_group, units_donated, health_screening, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $donor_id, $donation_date, $blood_group, $units_donated, $health_screening, $status);
    
    if ($stmt->execute()) {
        // Update donor's last donation date if donation was completed
        if ($status === 'Completed') {
            $update_stmt = $conn->prepare("UPDATE donors SET last_donation_date = ? WHERE id = ?");
            $update_stmt->bind_param("si", $donation_date, $donor_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            // Update blood inventory
            $inventory_stmt = $conn->prepare("UPDATE blood_inventory SET units_available = units_available + ? WHERE blood_group = ?");
            $inventory_stmt->bind_param("ds", $units_donated, $blood_group);
            $inventory_stmt->execute();
            $inventory_stmt->close();
        }
        
        $_SESSION['success'] = "Donation processed successfully!";
    } else {
        $_SESSION['error'] = "Failed to process donation. Please try again.";
    }
    
    $stmt->close();
    header("Location: ../admin/donations.php");
    exit();
} else {
    header("Location: ../admin/donations.php");
    exit();
}
?>