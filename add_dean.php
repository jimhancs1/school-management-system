<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $hire_date = $_POST['hire_date'];
    $faculty_id = $_POST['faculty_id'];
    $contact_info_id = $_POST['contact_info_id'];
    $staff_id = $_POST['staff_id'];

    $required = ['first_name', 'last_name', 'hire_date', 'contact_info_id', 'staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO dean (first_name, last_name, hire_date, faculty_id, contact_info_id, staff_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $first_name, $last_name, $hire_date, $faculty_id, $contact_info_id, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Dean added successfully!</div>';
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
    <title>Add Dean</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Dean</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Faculty</label>
                <select name="faculty_id" class="form-control">
                    <option value="">Select Faculty</option>
                    <?php
                    $result = $conn->query("SELECT faculty_id, faculty_name FROM faculty");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['faculty_id']}'>{$row['faculty_name']}</option>";
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
            <button type="submit" class="btn btn-primary">Add Dean</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>