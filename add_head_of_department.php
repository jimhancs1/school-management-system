<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $person_id = $_POST['person_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $remarks = $_POST['remarks'];
    $staff_id = $_POST['staff_id'];
    $contact_info_id = $_POST['contact_info_id'];

    $required = ['department_id', 'person_id', 'start_date', 'staff_id', 'contact_info_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO head_of_department (department_id, person_id, start_date, end_date, remarks, staff_id, contact_info_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissiii", $department_id, $person_id, $start_date, $end_date, $remarks, $staff_id, $contact_info_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Head of Department added successfully!</div>';
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
    <title>Add Head of Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Head of Department</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-control" required>
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
                <label class="form-label">Person ID</label>
                <input type="number" name="person_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control"></textarea>
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
            <button type="submit" class="btn btn-primary">Add Head of Department</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>