<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $required = ['student_id', 'class_id', 'attendance_date'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, class_id, attendance_date, status, remarks) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisis", $student_id, $class_id, $attendance_date, $status, $remarks);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Attendance added successfully!</div>';
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
    <title>Add Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Attendance</h2>
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
                <label class="form-label">Class</label>
                <select name="class_id" class="form-control" required>
                    <option value="">Select Class</option>
                    <?php
                    $result = $conn->query("SELECT class_id, room FROM classes");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['class_id']}'>{$row['room']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Attendance Date</label>
                <input type="date" name="attendance_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="number" name="status" class="form-control" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Attendance</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>