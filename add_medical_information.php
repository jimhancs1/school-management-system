<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $hospital_name = $_POST['hospital_name'];
    $doctor_name = $_POST['doctor_name'];
    $blood_type = $_POST['blood_type'];
    $known_allergies = $_POST['known_allergies'];
    $medical_conditions = $_POST['medical_conditions'];
    $immuniaztion_record = $_POST['immuniaztion_record'];
    $health_insurance_name = $_POST['health_insurance_name'];
    $staff_id = $_POST['staff_id'];

    $required = ['student_id', 'blood_type', 'staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO medical_information (student_id, hospital_name, doctor_name, blood_type, known_allergies, medical_conditions, immuniaztion_record, health_insurance_name, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssi", $student_id, $hospital_name, $doctor_name, $blood_type, $known_allergies, $medical_conditions, $immuniaztion_record, $health_insurance_name, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Medical Information added successfully!</div>';
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
    <title>Add Medical Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Medical Information</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    <?php
                    $result = $conn->query("SELECT student_id, first_name FROM student");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['student_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hospital Name</label>
                <input type="text" name="hospital_name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Doctor Name</label>
                <input type="text" name="doctor_name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Blood Type</label>
                <input type="text" name="blood_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Known Allergies</label>
                <input type="text" name="known_allergies" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Medical Conditions</label>
                <input type="text" name="medical_conditions" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Immuniaztion Record</label>
                <input type="text" name="immuniaztion_record" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Health Insurance Name</label>
                <input type="text" name="health_insurance_name" class="form-control">
            </div>
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
            <button type="submit" class="btn btn-primary">Add Medical Information</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>