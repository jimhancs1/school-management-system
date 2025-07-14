<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $religion = $_POST['religion'];
    $marital_status = $_POST['marital_status'];
    $relationship = $_POST['relationship'];
    $contact_info_id = $_POST['contact_info_id'];
    $student_id = $_POST['student_id'];
    $staff_id = $_POST['staff_id'];
    $language = $_POST['language'];
    $Occupation = $_POST['Occupation'];

    $required = ['contact_info_id', 'Occupation'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO next_of_kin (first_name, last_name, date_of_birth, gender, nationality, religion, marital_status, relationship, contact_info_id, student_id, staff_id, language, Occupation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssiiiiii", $first_name, $last_name, $date_of_birth, $gender, $nationality, $religion, $marital_status, $relationship, $contact_info_id, $student_id, $staff_id, $language, $Occupation);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Next of Kin added successfully!</div>';
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
    <title>Add Next of Kin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Next of Kin</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <input type="text" name="gender" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Marital Status</label>
                <input type="text" name="marital_status" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Relationship</label>
                <input type="text" name="relationship" class="form-control">
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
                <label class="form-label">Staff</label>
                <select name="staff_id" class="form-control">
                    <option value="">Select Staff</option>
                    <?php
                    $result = $conn->query("SELECT staff_id, first_name FROM staff");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['staff_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Language</label>
                <input type="text" name="language" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Occupation</label>
                <input type="number" name="Occupation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Next of Kin</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>