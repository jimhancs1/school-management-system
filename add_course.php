<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $department_id = $_POST['department_id'];
    $course_description = $_POST['course_description'];
    $course_level = $_POST['course_level'];
    $course_duration = $_POST['course_duration'];
    $total_credits = $_POST['total_credits'];
    $admission_requirements = $_POST['admission_requirements'];
    $award_title = $_POST['award_title'];

    $required = ['course_code', 'course_name', 'course_level', 'course_duration', 'total_credits', 'admission_requirements', 'award_title'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO course (course_code, course_name, department_id, course_description, course_level, course_duration, total_credits, admission_requirements, award_title) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissssss", $course_code, $course_name, $department_id, $course_description, $course_level, $course_duration, $total_credits, $admission_requirements, $award_title);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Course added successfully!</div>';
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
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Course</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Course Code</label>
                <input type="text" name="course_code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
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
                <label class="form-label">Course Description</label>
                <textarea name="course_description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Level</label>
                <input type="text" name="course_level" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Duration</label>
                <input type="text" name="course_duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Total Credits</label>
                <input type="text" name="total_credits" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Admission Requirements</label>
                <input type="text" name="admission_requirements" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Award Title</label>
                <input type="text" name="award_title" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>