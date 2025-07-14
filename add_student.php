<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $department_id = $_POST['department_id'] ?? null;
    $next_of_kin_id = $_POST['next_of_kin_id'] ?? null;
    $medical_info_id = $_POST['medical_info_id'] ?? null;
    $financial_details_id = $_POST['financial_details_id'] ?? null;
    $contact_info_id = $_POST['contact_info_id'] ?? null;
    $language = $_POST['language'] ?? '';

    $required = ['first_name', 'last_name', 'date_of_birth', 'next_of_kin_id', 'medical_info_id', 'financial_details_id', 'contact_info_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please fill all required fields: ' . implode(', ', $missing) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } else {
        if (!$conn) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Database connection failed.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO person (first_name, last_name, date_of_birth, department_id, next_of_kin_id, medical_info_id, financial_details_id, contact_info_id, language) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssiiiis", $first_name, $last_name, $date_of_birth, $department_id, $next_of_kin_id, $medical_info_id, $financial_details_id, $contact_info_id, $language);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">Person added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error: ' . htmlspecialchars($stmt->error) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Failed to prepare statement.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Person</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Person</h2>
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
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-control">
                    <option value="">Select Department</option>
                    <?php
                    if ($conn) {
                        $result = $conn->query("SELECT department_id, department_name FROM department");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['department_id']}'>" . htmlspecialchars($row['department_name']) . "</option>";
                            }
                            $result->free();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Next of Kin</label>
                <select name="next_of_kin_id" class="form-control" required>
                    <option value="">Select Next of Kin</option>
                    <?php
                    if ($conn) {
                        $result = $conn->query("SELECT next_of_kin_id, next_of_kin_first_name FROM next_of_kin");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['next_of_kin_id']}'>" . htmlspecialchars($row['next_of_kin_first_name']) . "</option>";
                            }
                            $result->free();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Medical Info</label>
                <select name="medical_info_id" class="form-control" required>
                    <option value="">Select Medical Info</option>
                    <?php
                    if ($conn) {
                        $result = $conn->query("SELECT medical_info_id, blood_type FROM medical_information");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['medical_info_id']}'>" . htmlspecialchars($row['blood_type']) . "</option>";
                            }
                            $result->free();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Financial Details</label>
                <select name="financial_details_id" class="form-control" required>
                    <option value="">Select Financial Details</option>
                    <?php
                    if ($conn) {
                        $result = $conn->query("SELECT financial_details_id, fi_name FROM financial_details");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['financial_details_id']}'>" . htmlspecialchars($row['fi_name']) . "</option>";
                            }
                            $result->free();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Info</label>
                <select name="contact_info_id" class="form-control" required>
                    <option value="">Select Contact Info</option>
                    <?php
                    if ($conn) {
                        $result = $conn->query("SELECT contact_info_id, personal_email FROM contact_info");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['contact_info_id']}'>" . htmlspecialchars($row['personal_email']) . "</option>";
                            }
                            $result->free();
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Language</label>
                <input type="text" name="language" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Add Person</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>