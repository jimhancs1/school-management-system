<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_name = $_POST['department_name'];
    $faculty_id = $_POST['faculty_id'];
    $head_of_department_id = $_POST['head_of_department_id'];
    $department_abbreviation = $_POST['department_abbreviation'];
    $office_location = $_POST['office_location'];
    $email_address = $_POST['email_address'];
    $established_year = $_POST['established_year'];
    $phone_number = $_POST['phone_number'];

    $required = ['department_name', 'head_of_department_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO department (department_name, faculty_id, head_of_department_id, department_abbreviation, office_location, email_address, established_year, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssss", $department_name, $faculty_id, $head_of_department_id, $department_abbreviation, $office_location, $email_address, $established_year, $phone_number);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Department added successfully!</div>';
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
    <title>Add Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Department</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Department Name</label>
                <input type="text" name="department_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Faculty</label>
                <select name="faculty_id" class="form-control">
                    <option value="">Select Faculty</option>
                    <?php
                    $result = $conn->query("SELECT faculty_id, faculty_name FROM faculty");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['faculty_id']}'>{$row['faculty_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Head of Department</label>
                <select name="head_of_department_id" class="form-control" required>
                    <option value="">Select Head of Department</option>
                    <?php
                    $result = $conn->query("SELECT hod_id, person_id FROM head_of_department");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['hod_id']}'>{$row['person_id']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Department Abbreviation</label>
                <input type="text" name="department_abbreviation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Office Location</label>
                <input type="text" name="office_location" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="text" name="email_address" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Established Year</label>
                <input type="text" name="established_year" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Department</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>