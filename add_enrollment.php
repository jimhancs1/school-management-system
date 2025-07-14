<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $class_id = $_POST['class_id'];
    $enrollment_date = $_POST['enrollment_date'];
    $enrollment_status = $_POST['enrollment_status'];

    $required = ['enrollment_date', 'enrollment_status'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO enrollment (student_id, class_id, enrollment_date, enrollment_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $class_id, $enrollment_date, $enrollment_status);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Enrollment added successfully!</div>';
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
    <title>Add Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Enrollment</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-control">
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
                <select name="class_id" class="form-control">
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
                <label class="form-label">Enrollment Date</label>
                <input type="date" name="enrollment_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Enrollment Status</label>
                <input type="text" name="enrollment_status" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Enrollment</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>