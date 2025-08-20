<?php
require_once 'includes/config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if(isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $user_type = trim($_POST['user_type']);
    
    if(!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if($password === $confirm_password) {
            if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if($stmt->rowCount() == 0) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
                    if($stmt->execute([$name, $email, $hashed_password, $user_type])) {
                        $success = "Registration successful! You can now login.";
                    } else {
                        $error = "Something went wrong. Please try again.";
                    }
                } else {
                    $error = "Email already exists!";
                }
            } else {
                $error = "Invalid email format!";
            }
        } else {
            $error = "Passwords do not match!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Register</h3>
            </div>
            <div class="card-body">
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="user_type" class="form-label">I want to register as:</label>
                        <select class="form-select" id="user_type" name="user_type" required>
                            <option value="">Select Type</option>
                            <option value="donor">Blood Donor</option>
                            <option value="bank">Blood Bank</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-danger">Register</button>
                </form>
                
                <div class="mt-3">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>