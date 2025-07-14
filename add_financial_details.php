<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $staff_id = $_POST['staff_id'];
    $fi_name = $_POST['fi_name'];
    $fi_number = $_POST['fi_number'];
    $fi_location = $_POST['fi_location'];
    $finance_method = $_POST['finance_method'];

    $required = ['student_id', 'staff_id', 'fi_name', 'fi_number', 'finance_method'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO financial_details (student_id, staff_id, fi_name, fi_number, fi_location, finance_method) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissii", $student_id, $staff_id, $fi_name, $fi_number, $fi_location, $finance_method);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Financial Details added successfully!</div>';
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
    <title>Add Financial Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Financial Details</h2>
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
                <label class="form-label">Fi Name</label>
                <input type="text" name="fi_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fi Number</label>
                <input type="text" name="fi_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fi Location</label>
                <input type="text" name="fi_location" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Finance Method</label>
                <input type="number" name="finance_method" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Financial Details</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>