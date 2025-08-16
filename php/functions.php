<?php
// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if user is logged in as admin
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Function to check if user is logged in as donor
function is_donor_logged_in() {
    return isset($_SESSION['donor_id']);
}

// Function to get blood inventory
function get_blood_inventory($conn) {
    $inventory = array();
    $query = "SELECT blood_group, units_available FROM blood_inventory ORDER BY blood_group";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $inventory[$row['blood_group']] = $row['units_available'];
        }
    }
    
    return $inventory;
}

// Function to get donor information
function get_donor_info($conn, $donor_id) {
    $stmt = $conn->prepare("SELECT * FROM donors WHERE id = ?");
    $stmt->bind_param("i", $donor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Function to get all blood requests
function get_blood_requests($conn, $status = null) {
    $requests = array();
    
    if ($status) {
        $stmt = $conn->prepare("SELECT * FROM requests WHERE status = ? ORDER BY required_date ASC");
        $stmt->bind_param("s", $status);
    } else {
        $stmt = $conn->prepare("SELECT * FROM requests ORDER BY required_date ASC");
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }
    
    return $requests;
}

// Function to get donor donations
function get_donor_donations($conn, $donor_id) {
    $donations = array();
    $stmt = $conn->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY donation_date DESC");
    $stmt->bind_param("i", $donor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
    }
    
    return $donations;
}
?>