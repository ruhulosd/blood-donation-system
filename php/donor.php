<?php
require_once 'includes/config.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Handle donor profile creation/update
if(isset($_POST['save_profile'])) {
    $full_name = trim($_POST['full_name']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $blood_group = trim($_POST['blood_group']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $last_donation_date = trim($_POST['last_donation_date']);
    $health_report = trim($_POST['health_report']);
    
    // Check if donor profile already exists
    $stmt = $pdo->prepare("SELECT id FROM donors WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    if($stmt->rowCount() > 0) {
        // Update existing profile
        $stmt = $pdo->prepare("UPDATE donors SET full_name=?, age=?, gender=?, blood_group=?, contact_number=?, address=?, last_donation_date=?, health_report=? WHERE user_id=?");
        $stmt->execute([$full_name, $age, $gender, $blood_group, $contact_number, $address, $last_donation_date, $health_report, $user_id]);
        $success = "Profile updated successfully!";
    } else {
        // Create new profile
        $stmt = $pdo->prepare("INSERT INTO donors (user_id, full_name, age, gender, blood_group, contact_number, address, last_donation_date, health_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $full_name, $age, $gender, $blood_group, $contact_number, $address, $last_donation_date, $health_report]);
        $success = "Profile created successfully!";
    }
}

// Get donor profile if exists
$stmt = $pdo->prepare("SELECT * FROM donors WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

require_once 'includes/header.php';

// Show profile form if requested or if profile doesn't exist
if((isset($_GET['action']) && $_GET['action'] == 'profile') || !$profile) {
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0"><?php echo $profile ? 'Update' : 'Complete'; ?> Donor Profile</h3>
            </div>
            <div class="card-body">
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $profile ? $profile['full_name'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" value="<?php echo $profile ? $profile['age'] : ''; ?>" required min="18" max="65">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo ($profile && $profile['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($profile && $profile['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($profile && $profile['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-select" id="blood_group" name="blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+" <?php echo ($profile && $profile['blood_group'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                                <option value="A-" <?php echo ($profile && $profile['blood_group'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                                <option value="B+" <?php echo ($profile && $profile['blood_group'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                                <option value="B-" <?php echo ($profile && $profile['blood_group'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                                <option value="AB+" <?php echo ($profile && $profile['blood_group'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                                <option value="AB-" <?php echo ($profile && $profile['blood_group'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                                <option value="O+" <?php echo ($profile && $profile['blood_group'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                                <option value="O-" <?php echo ($profile && $profile['blood_group'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="tel" class="form-control" id="contact_number" name="contact_number" value="<?php echo $profile ? $profile['contact_number'] : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $profile ? $profile['address'] : ''; ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="last_donation_date" class="form-label">Last Donation Date (if any)</label>
                            <input type="date" class="form-control" id="last_donation_date" name="last_donation_date" value="<?php echo $profile ? $profile['last_donation_date'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="health_report" class="form-label">Health Report (any conditions?)</label>
                            <input type="text" class="form-control" id="health_report" name="health_report" value="<?php echo $profile ? $profile['health_report'] : ''; ?>">
                        </div>
                    </div>
                    
                    <button type="submit" name="save_profile" class="btn btn-danger">Save Profile</button>
                    <a href="donor.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
} else {
// Show donor list or donor dashboard
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Donors</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="donor.php?action=profile" class="btn btn-sm btn-outline-primary">My Profile</a>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Donor Profile</h5>
                <p><strong>Name:</strong> <?php echo $profile['full_name']; ?></p>
                <p><strong>Blood Group:</strong> <span class="badge bg-danger"><?php echo $profile['blood_group']; ?></span></p>
                <p><strong>Status:</strong> <span class="badge bg-<?php echo $profile['status'] == 'Available' ? 'success' : 'secondary'; ?>"><?php echo $profile['status']; ?></span></p>
                <a href="donor.php?action=profile" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="emergency.php" class="btn btn-outline-danger">View Emergency Requests</a>
                    <button class="btn btn-outline-secondary">Update Availability Status</button>
                    <button class="btn btn-outline-info">Donation History</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Available Donors</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Age</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all donors
                    $stmt = $pdo->query("SELECT * FROM donors WHERE status = 'Available' ORDER BY blood_group");
                    while($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['full_name']}</td>
                            <td><span class='badge bg-danger'>{$row['blood_group']}</span></td>
                            <td>{$row['age']}</td>
                            <td>{$row['contact_number']}</td>
                            <td>".substr($row['address'], 0, 20)."...</td>
                            <td><span class='badge bg-success'>{$row['status']}</span></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
}
require_once 'includes/footer.php';
?>