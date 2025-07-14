<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $resume = $_POST['resume'];
    $certificates = $_POST['certificates'];
    $national_id = $_POST['national_id'];
    $acceptance_letter = $_POST['acceptance_letter'];
    $recommendation_letter = $_POST['recommendation_letter'];
    $professional_liscence = $_POST['professional_liscence'];
    $police_verification = $_POST['police_verification'];
    $experience_letter = $_POST['experience_letter'];

    $required = ['staff_id', 'professional_liscence'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO employment_documents (staff_id, resume, certificates, national_id, acceptance_letter, recommendation_letter, professional_liscence, police_verification, experience_letter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $staff_id, $resume, $certificates, $national_id, $acceptance_letter, $recommendation_letter, $professional_liscence, $police_verification, $experience_letter);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Employment Documents added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employment Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Employment Documents</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Staff</label>
                <select name="staff_id" class="form-control" required>
                    <option value="">Select Staff</option>
                    <?php
                    $result = $conn->query("SELECT staff_id, first_name FROM staff");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['staff_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Resume</label>
                <input type="text" name="resume" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Certificates</label>
                <input type="text" name="certificates" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">National ID</label>
                <input type="text" name="national_id" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Acceptance Letter</label>
                <input type="text" name="acceptance_letter" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Recommendation Letter</label>
                <input type="text" name="recommendation_letter" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Professional Liscence</label>
                <input type="text" name="professional_liscence" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Police Verification</label>
                <input type="text" name="police_verification" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Experience Letter</label>
                <input type="text" name="experience_letter" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Employment Documents</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>