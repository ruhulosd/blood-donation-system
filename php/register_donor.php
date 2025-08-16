<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $blood_group = sanitize_input($_POST['blood_group']);
    $gender = sanitize_input($_POST['gender']);
    $dob = sanitize_input($_POST['dob']);
    $address = sanitize_input($_POST['address']);
    $health_info = sanitize_input($_POST['health_info']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered. Please use a different email.";
        header("Location: ../donate.html");
        exit();
    }
    $stmt->close();

    // Handle last donation date (can be empty)
    $last_donation_date = !empty($_POST['last_donation']) ? sanitize_input($_POST['last_donation']) : null;

    // Insert donor into database
    $stmt = $conn->prepare("INSERT INTO donors (name, email, phone, blood_group, gender, dob, address, last_donation_date, health_info, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $name, $email, $phone, $blood_group, $gender, $dob, $address, $last_donation_date, $health_info, $password);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now login to your donor account.";
        header("Location: ../login.html");
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../donate.html");
    }
    
    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../donate.html");
    exit();
}
?>