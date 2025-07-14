<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $semester_name = $_POST['semester_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $required = ['semester_name', 'start_date', 'end_date'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO semester (semester_name, start_date, end_date) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $semester_name, $start_date, $end_date);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Semester added successfully!</div>';
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
    <title>Add Semester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Semester</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Semester Name</label>
                <input type="text" name="semester_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Semester</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>