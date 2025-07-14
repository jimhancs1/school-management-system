<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $faculty_name = $_POST['faculty_name'];
    $faculty_abbreviation = $_POST['faculty_abbreviation'];
    $establishment_date = $_POST['establishment_date'];
    $accreditation_status = $_POST['accreditation_status'];
    $location = $_POST['location'];
    $phone_number = $_POST['phone_number'];
    $email_address = $_POST['email_address'];
    $dean_id = $_POST['dean_id'];

    $required = ['faculty_name', 'dean_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO faculty (faculty_name, faculty_abbreviation, establishment_date, accreditation_status, location, phone_number, email_address, dean_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $faculty_name, $faculty_abbreviation, $establishment_date, $accreditation_status, $location, $phone_number, $email_address, $dean_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Faculty added successfully!</div>';
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
    <title>Add Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Faculty</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Faculty Name</label>
                <input type="text" name="faculty_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Faculty Abbreviation</label>
                <input type="text" name="faculty_abbreviation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Establishment Date</label>
                <input type="date" name="establishment_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Accreditation Status</label>
                <input type="text" name="accreditation_status" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone_number" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="text" name="email_address" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Dean</label>
                <select name="dean_id" class="form-control" required>
                    <option value="">Select Dean</option>
                    <?php
                    $result = $conn->query("SELECT dean_id, first_name FROM dean");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['dean_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Faculty</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>