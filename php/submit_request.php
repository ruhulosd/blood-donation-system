<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $patient_name = sanitize_input($_POST['patient_name']);
    $hospital_name = sanitize_input($_POST['hospital_name']);
    $blood_group = sanitize_input($_POST['blood_group']);
    $units_required = (int)$_POST['units_required'];
    $urgency = sanitize_input($_POST['urgency']);
    $request_date = sanitize_input($_POST['request_date']);
    $required_date = sanitize_input($_POST['required_date']);
    $contact_person = sanitize_input($_POST['contact_person']);
    $contact_phone = sanitize_input($_POST['contact_phone']);
    $notes = sanitize_input($_POST['notes']);

    // Check if required date is valid
    if (strtotime($required_date) < strtotime($request_date)) {
        $_SESSION['error'] = "Required date cannot be before request date.";
        header("Location: ../request.html");
        exit();
    }

    // Check if units required is valid
    if ($units_required < 1) {
        $_SESSION['error'] = "Units required must be at least 1.";
        header("Location: ../request.html");
        exit();
    }

    // Insert request into database
    $stmt = $conn->prepare("INSERT INTO requests (patient_name, hospital_name, blood_group, units_required, urgency, request_date, required_date, contact_person, contact_phone, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissssss", $patient_name, $hospital_name, $blood_group, $units_required, $urgency, $request_date, $required_date, $contact_person, $contact_phone, $notes);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Blood request submitted successfully! We'll contact you shortly.";
        header("Location: ../request.html");
    } else {
        $_SESSION['error'] = "Request submission failed. Please try again.";
        header("Location: ../request.html");
    }
    
    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../request.html");
    exit();
}
?>