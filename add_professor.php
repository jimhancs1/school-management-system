<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $hire_date = $_POST['hire_date'];
    $department_id = $_POST['department_id'];
    $contact_info_id = $_POST['contact_info_id'];

    $required = ['first_name', 'last_name', 'hire_date', 'contact_info_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO professor (first_name, last_name, hire_date, department_id, contact_info_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $first_name, $last_name, $hire_date, $department_id, $contact_info_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Professor added successfully!</div>';
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
    <title>Add Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Professor</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-control" required>
            </div>
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
                <label class="form-label">Contact Info</label>
                <select name="contact_info_id" class="form-control" required>
                    <option value="">Select Contact Info</option>
                    <?php
                    $result = $conn->query("SELECT contact_info_id, personal_email FROM contact_info");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['contact_info_id']}'>{$row['personal_email']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Professor</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
