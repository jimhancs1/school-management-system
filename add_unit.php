<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unit_code = $_POST['unit_code'];
    $unit_name = $_POST['unit_name'];
    $course_id = $_POST['course_id'];
    $professor_id = $_POST['professor_id'];
    $unit_status = $_POST['unit_status'];
    $unit_description = $_POST['unit_description'];

    $required = ['unit_code', 'unit_name', 'unit_status', 'unit_description'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO unit (unit_code, unit_name, course_id, professor_id, unit_status, unit_description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiss", $unit_code, $unit_name, $course_id, $professor_id, $unit_status, $unit_description);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Unit added successfully!</div>';
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
    <title>Add Unit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Unit</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Unit Code</label>
                <input type="text" name="unit_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Name</label>
                <input type="text" name="unit_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-control">
                    <option value="">Select Course</option>
                    <?php
                    $result = $conn->query("SELECT course_id, course_name FROM course");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['course_id']}'>{$row['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Professor</label>
                <select name="professor_id" class="form-control">
                    <option value="">Select Professor</option>
                    <?php
                    $result = $conn->query("SELECT professor_id, first_name FROM professor");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['professor_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Status</label>
                <input type="text" name="unit_status" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Description</label>
                <input type="text" name="unit_description" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Unit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>