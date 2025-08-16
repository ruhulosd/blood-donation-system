<?php
require_once '../php/config.php';
require_once '../php/functions.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    header("Location: ../admin/login.html");
    exit();
}

// Get statistics for dashboard
$donors_count = $conn->query("SELECT COUNT(*) FROM donors")->fetch_row()[0];
$donations_count = $conn->query("SELECT COUNT(*) FROM donations WHERE status = 'Completed'")->fetch_row()[0];
$requests_count = $conn->query("SELECT COUNT(*) FROM requests")->fetch_row()[0];
$pending_requests = $conn->query("SELECT COUNT(*) FROM requests WHERE status = 'Pending'")->fetch_row()[0];

// Get recent donations
$recent_donations = $conn->query("SELECT d.*, donors.name FROM donations d JOIN donors ON d.donor_id = donors.id ORDER BY d.donation_date DESC LIMIT 5");

// Get urgent requests
$urgent_requests = $conn->query("SELECT * FROM requests WHERE urgency = 'Critical' AND status = 'Pending' ORDER BY required_date ASC LIMIT 5");

// Get blood inventory levels
$inventory = get_blood_inventory($conn);
?>