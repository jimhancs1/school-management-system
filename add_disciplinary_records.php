<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $incident_date = $_POST['incident_date'];
    $incident_description = $_POST['incident_description'];
    $action_taken = $_POST['action_taken'];
    $recorded_by = $_POST['recorded_by'];
    $staff_id = $_POST['staff_id'];

    $required = ['student_id', 'incident_date', 'staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO disciplinary_records (student_id, incident_date, incident_description, action_taken, recorded_by, staff_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssii", $student_id, $incident_date, $incident_description, $action_taken, $recorded_by, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Disciplinary Records added successfully!</div>';
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
    <title>Add Disciplinary Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Disciplinary Records</h2>
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
                <label class="form-label">Incident Date</label>
                <input type="date" name="incident_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Incident Description</label>
                <textarea name="incident_description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Action Taken</label>
                <textarea name="action_taken" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Recorded By</label>
                <input type="number" name="recorded_by" class="form-control">
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
            <button type="submit" class="btn btn-primary">Add Disciplinary Records</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>