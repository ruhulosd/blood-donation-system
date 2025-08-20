<?php
require_once 'includes/config.php';

// Handle emergency request submission
if(isset($_POST['submit_request'])) {
    $patient_name = trim($_POST['patient_name']);
    $required_blood_group = trim($_POST['required_blood_group']);
    $units_required = trim($_POST['units_required']);
    $hospital_name = trim($_POST['hospital_name']);
    $hospital_address = trim($_POST['hospital_address']);
    $contact_person = trim($_POST['contact_person']);
    $contact_number = trim($_POST['contact_number']);
    $urgency_level = trim($_POST['urgency_level']);
    $additional_notes = trim($_POST['additional_notes']);
    
    if(!empty($patient_name) && !empty($required_blood_group) && !empty($units_required) && 
       !empty($hospital_name) && !empty($hospital_address) && !empty($contact_person) && 
       !empty($contact_number) && !empty($urgency_level)) {
        
        $stmt = $pdo->prepare("INSERT INTO emergency_requests (patient_name, required_blood_group, units_required, hospital_name, hospital_address, contact_person, contact_number, urgency_level, additional_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if($stmt->execute([$patient_name, $required_blood_group, $units_required, $hospital_name, $hospital_address, $contact_person, $contact_number, $urgency_level, $additional_notes])) {
            $success = "Emergency request submitted successfully! We'll contact you shortly.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "Please fill all required fields!";
    }
}

require_once 'includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Emergency Blood Request</h3>
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
                            <label for="patient_name" class="form-label">Patient Name *</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="required_blood_group" class="form-label">Required Blood Group *</label>
                            <select class="form-select" id="required_blood_group" name="required_blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="units_required" class="form-label">Units Required *</label>
                            <input type="number" class="form-control" id="units_required" name="units_required" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="urgency_level" class="form-label">Urgency Level *</label>
                            <select class="form-select" id="urgency_level" name="urgency_level" required>
                                <option value="">Select Urgency</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hospital_name" class="form-label">Hospital Name *</label>
                        <input type="text" class="form-control" id="hospital_name" name="hospital_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hospital_address" class="form-label">Hospital Address *</label>
                        <textarea class="form-control" id="hospital_address" name="hospital_address" rows="2" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_person" class="form-label">Contact Person *</label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number *</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="additional_notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" name="submit_request" class="btn btn-danger">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Emergency Contacts</h5>
            </div>
            <div class="card-body">
                <p><strong>Emergency Helpline:</strong> 10666</p>
                <p><strong>Blood Bank Authority:</strong> 0123456789</p>
                <p><strong>24/7 Support:</strong> support@blooddonation.org</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">Recent Emergency Requests</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM emergency_requests ORDER BY created_at DESC LIMIT 5");
                    while($row = $stmt->fetch()) {
                        $urgencyClass = '';
                        if($row['urgency_level'] == 'Critical') $urgencyClass = 'danger';
                        elseif($row['urgency_level'] == 'High') $urgencyClass = 'warning';
                        else $urgencyClass = 'info';
                        
                        echo "<li class='list-group-item'>
                            <div class='d-flex justify-content-between'>
                                <strong>{$row['required_blood_group']}</strong>
                                <span class='badge bg-{$urgencyClass}'>{$row['urgency_level']}</span>
                            </div>
                            <small>{$row['hospital_name']} - ".date('M j, Y', strtotime($row['created_at']))."</small>
                        </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>