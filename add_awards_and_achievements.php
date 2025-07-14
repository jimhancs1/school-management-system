<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $awarded_by = $_POST['awarded_by'];
    $award_date = $_POST['award_date'];
    $level = $_POST['level'];

    $required = ['student_id', 'level'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO awards_and_achievements (student_id, title, description, awarded_by, award_date, level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $student_id, $title, $description, $awarded_by, $award_date, $level);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Award added successfully!</div>';
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
    <title>Add Awards and Achievements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Awards and Achievements</h2>
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
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Awarded By</label>
                <input type="text" name="awarded_by" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Award Date</label>
                <input type="date" name="award_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Level</label>
                <input type="text" name="level" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Award</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>