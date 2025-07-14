<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $unit_id = $_POST['unit_id'];
    $grade = $_POST['grade'];
    $semester_id = $_POST['semester_id'];
    $graded_by = $_POST['graded_by'];
    $grade_date = $_POST['grade_date'];
    $professor_id = $_POST['professor_id'];

    $required = ['grade', 'graded_by', 'grade_date', 'professor_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO grades (student_id, unit_id, grade, semester_id, graded_by, grade_date, professor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisisii", $student_id, $unit_id, $grade, $semester_id, $graded_by, $grade_date, $professor_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Grades added successfully!</div>';
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
    <title>Add Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Grades</h2>
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
                <label class="form-label">Unit</label>
                <select name="unit_id" class="form-control">
                    <option value="">Select Unit</option>
                    <?php
                    $result = $conn->query("SELECT unit_id, unit_name FROM unit");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['unit_id']}'>{$row['unit_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Grade</label>
                <input type="text" name="grade" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-control">
                    <option value="">Select Semester</option>
                    <?php
                    $result = $conn->query("SELECT semester_id, semester_name FROM semester");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['semester_id']}'>{$row['semester_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Graded By</label>
                <input type="text" name="graded_by" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Grade Date</label>
                <input type="text" name="grade_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Professor</label>
                <select name="professor_id" class="form-control" required>
                    <option value="">Select Professor</option>
                    <?php
                    $result = $conn->query("SELECT professor_id, first_name FROM professor");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['professor_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Grades</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>