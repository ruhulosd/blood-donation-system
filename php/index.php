<?php
require_once 'includes/header.php';
?>

<div class="hero-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-danger">Save Lives, Donate Blood</h1>
                <p class="lead">Join our community of blood donors and help those in need of life-saving blood donations.</p>
                <a href="register.php" class="btn btn-danger btn-lg">Become a Donor</a>
                <a href="emergency.php" class="btn btn-outline-danger btn-lg ms-2">Emergency Request</a>
            </div>
            <div class="col-md-6">
                <img src="images/blood-donation-hero.jpg" alt="Blood Donation" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<div class="features-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Donate Blood?</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fs-3">❤️</i>
                </div>
                <h4>Save Lives</h4>
                <p>Your blood donation can save up to three lives. Every donation counts.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fs-3">🔄</i>
                </div>
                <h4>Regular Updates</h4>
                <p>Get regular updates about blood requirements in your area.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fs-3">🏥</i>
                </div>
                <h4>Emergency Support</h4>
                <p>Be there for others during emergency situations requiring blood.</p>
            </div>
        </div>
    </div>
</div>

<div class="blood-stats py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Current Blood Availability</h2>
        <div class="row text-center">
            <?php
            $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            
            foreach($bloodGroups as $group) {
                // In a real application, you would query the database for actual counts
                $count = rand(10, 50); // Random count for demonstration
                $status = $count > 20 ? 'Good' : ($count > 10 ? 'Medium' : 'Low');
                
                echo "<div class='col-md-3 col-6 mb-4'>
                    <div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$group}</h5>
                            <p class='card-text display-6'>{$count}</p>
                            <span class='badge bg-".($status == 'Good' ? 'success' : ($status == 'Medium' ? 'warning' : 'danger'))."'>{$status}</span>
                        </div>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>