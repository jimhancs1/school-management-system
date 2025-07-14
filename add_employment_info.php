<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $job_title = $_POST['job_title'];
    $employment_type = $_POST['employment_type'];
    $hire_date = $_POST['hire_date'];
    $employee_status = $_POST['employee_status'];
    $supervisor = $_POST['supervisor'];
    $staff_category = $_POST['staff_category'];
    $employment_role = $_POST['employment_role'];
    $office_location = $_POST['office_location'];
    $office_number = $_POST['office_number'];
    $work_hours = $_POST['work_hours'];
    $staff_id = $_POST['staff_id'];

    $required = ['staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO employment_info (department_id, job_title, employment_type, hire_date, employee_status, supervisor, staff_category, employment_role, office_location, office_number, work_hours, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssssi", $department_id, $job_title, $employment_type, $hire_date, $employee_status, $supervisor, $staff_category, $employment_role, $office_location, $office_number, $work_hours, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Employment Info added successfully!</div>';
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
    <title>Add Employment Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Employment Info</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-control">
                    <option value="">Select Department</option>
                    <?php
                    $result = $conn->query("SELECT department_id, department_name FROM department");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['department_id']}'>{$row['department_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Job Title</label>
                <input type="text" name="job_title" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Type</label>
                <input type="text" name="employment_type" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Employee Status</label>
                <input type="text" name="employee_status" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Supervisor</label>
                <input type="text" name="supervisor" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Staff Category</label>
                <input type="text" name="staff_category" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Role</label>
                <input type="text" name="employment_role" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Office Location</label>
                <input type="text" name="office_location" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Office Number</label>
                <input type="text" name="office_number" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Work Hours</label>
                <input type="text" name="work_hours" class="form-control">
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
            <button type="submit" class="btn btn-primary">Add Employment Info</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>