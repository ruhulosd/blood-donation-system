<?php
require_once 'includes/config.php';

if(isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if(!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // In a real application, you would send an email or save to database
        $success = "Your message has been sent successfully! We'll get back to you soon.";
    } else {
        $error = "Please fill all fields!";
    }
}

require_once 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Contact Us</h3>
            </div>
            <div class="card-body">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" name="send_message" class="btn btn-danger">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Address:</strong> 123 Blood Donation Street, City, Country</p>
                <p><strong>Phone:</strong> +1 234 567 8900</p>
                <p><strong>Email:</strong> info@blooddonation.org</p>
                <p><strong>Hours:</strong> Monday - Friday: 9AM - 5PM</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Emergency Contacts</h5>
            </div>
            <div class="card-body">
                <p><strong>Emergency Helpline:</strong> 10666</p>
                <p><strong>24/7 Support:</strong> emergency@blooddonation.org</p>
                <p><strong>Blood Bank Authority:</strong> 0123456789</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>