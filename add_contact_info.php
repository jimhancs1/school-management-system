<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $county = $_POST['county'];
    $city_town = $_POST['city_town'];
    $district = $_POST['district'];
    $location = $_POST['location'];
    $personal_phone = $_POST['personal_phone'];
    $work_phone = $_POST['work_phone'];
    $personal_email = $_POST['personal_email'];
    $work_email = $_POST['work_email'];
    $student_id = $_POST['student_id'];
    $staff_id = $_POST['staff_id'];
    $next_of_kin_id = $_POST['next_of_kin_id'];
    $professor_id = $_POST['professor_id'];
    $dean_id = $_POST['dean_id'];
    $hod_id = $_POST['hod_id'];

    $required = ['location', 'personal_phone', 'personal_email'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_info (county, city_town, district, location, personal_phone, work_phone, personal_email, work_email, student_id, staff_id, next_of_kin_id, professor_id, dean_id, hod_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssiiiiii", $county, $city_town, $district, $location, $personal_phone, $work_phone, $personal_email, $work_email, $student_id, $staff_id, $next_of_kin_id, $professor_id, $dean_id, $hod_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Contact Info added successfully!</div>';
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
    <title>Add Contact Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Contact Info</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">County</label>
                <input type="text" name="county" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">City/Town</label>
                <input type="text" name="city_town" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">District</label>
                <input type="text" name="district" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Personal Phone</label>
                <input type="text" name="personal_phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Work Phone</label>
                <input type="text" name="work_phone" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Personal Email</label>
                <input type="email" name="personal_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Work Email</label>
                <input type="email" name="work_email" class="form-control">
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
                <label class="form-label">Next of Kin</label>
                <select name="next_of_kin_id" class="form-control">
                    <option value="">Select Next of Kin</option>
                    <?php
                    $result = $conn->query("SELECT next_of_kin_id, first_name FROM next_of_kin");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['next_of_kin_id']}'>{$row['first_name']}</option>";
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
                <label class="form-label">Dean</label>
                <select name="dean_id" class="form-control">
                    <option value="">Select Dean</option>
                    <?php
                    $result = $conn->query("SELECT dean_id, first_name FROM dean");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['dean_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Head of Department</label>
                <select name="hod_id" class="form-control">
                    <option value="">Select Head of Department</option>
                    <?php
                    $result = $conn->query("SELECT hod_id, person_id FROM head_of_department");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['hod_id']}'>{$row['person_id']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Contact Info</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>