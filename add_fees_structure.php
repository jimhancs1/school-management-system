<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department_id = $_POST['department_id'];
    $semester_id = $_POST['semester_id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $required = ['amount'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO fees_structure (department_id, semester_id, amount, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $department_id, $semester_id, $amount, $description);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Fees Structure added successfully!</div>';
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
    <title>Add Fees Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Fees Structure</h2>
        <form method="POST">
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
                <label class="form-label">Amount</label>
                <input type="text" name="amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Fees Structure</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>