<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Check donor credentials
    $stmt = $conn->prepare("SELECT id, name, password FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $donor = $result->fetch_assoc();
        if (password_verify($password, $donor['password'])) {
            $_SESSION['donor_id'] = $donor['id'];
            $_SESSION['donor_name'] = $donor['name'];
            header("Location: ../donor/dashboard.php");
            exit();
        }
    }
    
    // If login fails
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: ../donor/login.html");
    exit();
} else {
    header("Location: ../donor/login.html");
    exit();
}
?>