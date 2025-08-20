<?php
require_once 'includes/config.php';

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/header.php';

// Get user details
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Get additional info based on user type
if($user_type == 'donor') {
    $stmt = $pdo->prepare("SELECT * FROM donors WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
    
    // If donor profile doesn't exist, prompt to complete it
    if(!$profile) {
        echo "<div class='alert alert-info'>Please complete your donor profile <a href='donor.php?action=profile'>here</a>.</div>";
    }
} elseif($user_type == 'bank') {
    $stmt = $pdo->prepare("SELECT * FROM blood_banks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile = $stmt->fetch();
    
    // If bank profile doesn't exist, prompt to complete it
    if(!$profile) {
        echo "<div class='alert alert-info'>Please complete your blood bank profile <a href='blood_bank.php?action=profile'>here</a>.</div>";
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Emergency Requests</h5>
                <p class="card-text display-4">12</p>
                <a href="emergency.php" class="text-white">View details</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Available Donors</h5>
                <p class="card-text display-4">45</p>
                <a href="donor.php" class="text-white">View details</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Blood Banks</h5>
                <p class="card-text display-4">8</p>
                <a href="blood_bank.php" class="text-white">View details</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Activities</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">New emergency request for O+ blood</li>
                    <li class="list-group-item">3 new donors registered</li>
                    <li class="list-group-item">Blood bank ABC updated their inventory</li>
                    <li class="list-group-item">Emergency request #123 was fulfilled</li>
                    <li class="list-group-item">System maintenance completed</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Blood Availability</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                    
                    foreach($bloodGroups as $group) {
                        $count = rand(5, 30);
                        $status = $count > 20 ? 'Good' : ($count > 10 ? 'Medium' : 'Low');
                        
                        echo "<div class='col-6 mb-2'>
                            <div class='d-flex justify-content-between'>
                                <span>{$group}</span>
                                <span class='badge bg-".($status == 'Good' ? 'success' : ($status == 'Medium' ? 'warning' : 'danger'))."'>{$count} units</span>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>