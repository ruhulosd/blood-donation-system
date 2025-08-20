<?php
require_once 'includes/config.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Handle blood bank profile creation/update
if(isset($_POST['save_profile'])) {
    $bank_name = trim($_POST['bank_name']);
    $contact_person = trim($_POST['contact_person']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $blood_groups_available = implode(',', $_POST['blood_groups_available']);
    $license_number = trim($_POST['license_number']);
    
    // Check if blood bank profile already exists
    $stmt = $pdo->prepare("SELECT id FROM blood_banks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    if($stmt->rowCount() > 0) {
        // Update existing profile
        $stmt = $pdo->prepare("UPDATE blood_banks SET bank_name=?, contact_person=?, contact_number=?, address=?, blood_groups_available=?, license_number=? WHERE user_id=?");
        $stmt->execute([$bank_name, $contact_person, $contact_number, $address, $blood_groups_available, $license_number, $user_id]);
        $success = "Profile updated successfully!";
    } else {
        // Create new profile
        $stmt = $pdo->prepare("INSERT INTO blood_banks (user_id, bank_name, contact_person, contact_number, address, blood_groups_available, license_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $bank_name, $contact_person, $contact_number, $address, $blood_groups_available, $license_number]);
        $success = "Profile created successfully!";
    }
}

// Get blood bank profile if exists
$stmt = $pdo->prepare("SELECT * FROM blood_banks WHERE user_id = ?");
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
                <h3 class="card-title mb-0"><?php echo $profile ? 'Update' : 'Complete'; ?> Blood Bank Profile</h3>
            </div>
            <div class="card-body">
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Blood Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php echo $profile ? $profile['bank_name'] : ''; ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo $profile ? $profile['contact_person'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" value="<?php echo $profile ? $profile['contact_number'] : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo $profile ? $profile['address'] : ''; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Blood Groups Available</label>
                        <div class="row">
                            <?php
                            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                            $selectedGroups = $profile ? explode(',', $profile['blood_groups_available']) : [];
                            
                            foreach($bloodGroups as $group) {
                                echo "<div class='col-md-3 col-6 mb-2'>
                                    <div class='form-check'>
                                        <input class='form-check-input' type='checkbox' name='blood_groups_available[]' value='{$group}' id='bg_{$group}' ".(in_array($group, $selectedGroups) ? 'checked' : '').">
                                        <label class='form-check-label' for='bg_{$group}'>
                                            {$group}
                                        </label>
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="license_number" class="form-label">License Number</label>
                        <input type="text" class="form-control" id="license_number" name="license_number" value="<?php echo $profile ? $profile['license_number'] : ''; ?>" required>
                    </div>
                    
                    <button type="submit" name="save_profile" class="btn btn-danger">Save Profile</button>
                    <a href="blood_bank.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
} else {
// Show blood bank list or blood bank dashboard
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Blood Banks</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="blood_bank.php?action=profile" class="btn btn-sm btn-outline-primary">My Profile</a>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Blood Bank</h5>
                <p><strong>Name:</strong> <?php echo $profile['bank_name']; ?></p>
                <p><strong>Contact:</strong> <?php echo $profile['contact_person']; ?> (<?php echo $profile['contact_number']; ?>)</p>
                <p><strong>Available Blood Groups:</strong> 
                    <?php
                    $groups = explode(',', $profile['blood_groups_available']);
                    foreach($groups as $group) {
                        echo "<span class='badge bg-danger me-1'>{$group}</span>";
                    }
                    ?>
                </p>
                <a href="blood_bank.php?action=profile" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="emergency.php" class="btn btn-outline-danger">View Emergency Requests</a>
                    <button class="btn btn-outline-secondary">Update Inventory</button>
                    <button class="btn btn-outline-info">Request History</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Registered Blood Banks</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Bank Name</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>Available Blood Groups</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all blood banks
                    $stmt = $pdo->query("SELECT * FROM blood_banks WHERE status = 'Active' ORDER BY bank_name");
                    while($row = $stmt->fetch()) {
                        $groups = explode(',', $row['blood_groups_available']);
                        $groupBadges = '';
                        
                        foreach($groups as $group) {
                            $groupBadges .= "<span class='badge bg-danger me-1'>{$group}</span>";
                        }
                        
                        echo "<tr>
                            <td>{$row['bank_name']}</td>
                            <td>{$row['contact_person']}</td>
                            <td>{$row['contact_number']}</td>
                            <td>{$groupBadges}</td>
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