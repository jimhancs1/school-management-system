<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $national_id_no = $_POST['national_id_no'];
    $religion = $_POST['religion'];
    $marital_status = $_POST['marital_status'];
    $staff_admission_code = $_POST['staff_admission_code'];
    $employment_info_id = $_POST['employment_info_id'];
    $employment_qualification_id = $_POST['employment_qualification_id'];
    $performance_evaluation_id = $_POST['performance_evaluation_id'];
    $employment_documents_id = $_POST['employment_documents_id'];
    $staff_title = $_POST['staff_title'];
    $staff_role = $_POST['staff_role'];
    $contact_info_id = $_POST['contact_info_id'];
    $dean_id = $_POST['dean_id'];
    $hod_id = $_POST['hod_id'];
    $professor_id = $_POST['professor_id'];
    $finance_officer_id = $_POST['finance_officer_id'];
    $medical_info_id = $_POST['medical_info_id'];

    $required = ['employment_info_id', 'employment_qualification_id', 'performance_evaluation_id', 'employment_documents_id', 'staff_title', 'staff_role', 'contact_info_id', 'dean_id', 'hod_id', 'professor_id', 'finance_officer_id', 'medical_info_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO staff (first_name, last_name, date_of_birth, gender, nationality, national_id_no, religion, marital_status, staff_admission_code, employment_info_id, employment_qualification_id, performance_evaluation_id, employment_documents_id, staff_title, staff_role, contact_info_id, dean_id, hod_id, professor_id, finance_officer_id, medical_info_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssiiiiissiiiiii", $first_name, $last_name, $date_of_birth, $gender, $nationality, $national_id_no, $religion, $marital_status, $staff_admission_code, $employment_info_id, $employment_qualification_id, $performance_evaluation_id, $employment_documents_id, $staff_title, $staff_role, $contact_info_id, $dean_id, $hod_id, $professor_id, $finance_officer_id, $medical_info_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Staff added successfully!</div>';
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
    <title>Add Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Staff</h2>
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
                <label class="form-label">National ID No</label>
                <input type="text" name="national_id_no" class="form-control">
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
                <label class="form-label">Staff Admission Code</label>
                <input type="text" name="staff_admission_code" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Info</label>
                <select name="employment_info_id" class="form-control" required>
                    <option value="">Select Employment Info</option>
                    <?php
                    $result = $conn->query("SELECT employment_info_id, job_title FROM employment_info");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['employment_info_id']}'>{$row['job_title']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Qualification</label>
                <select name="employment_qualification_id" class="form-control" required>
                    <option value="">Select Employment Qualification</option>
                    <?php
                    $result = $conn->query("SELECT employment_qualification_id, degrees_aquired FROM employment_qualifications");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['employment_qualification_id']}'>{$row['degrees_aquired']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Performance Evaluation</label>
                <select name="performance_evaluation_id" class="form-control" required>
                    <option value="">Select Performance Evaluation</option>
                    <?php
                    $result = $conn->query("SELECT performance_evaluation_id, student_feedback FROM staff_performance_record");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['performance_evaluation_id']}'>{$row['student_feedback']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Documents</label>
                <select name="employment_documents_id" class="form-control" required>
                    <option value="">Select Employment Documents</option>
                    <?php
                    $result = $conn->query("SELECT employment_documents_id, national_id FROM employment_documents");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['employment_documents_id']}'>{$row['national_id']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Staff Title</label>
                <input type="text" name="staff_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Staff Role</label>
                <input type="text" name="staff_role" class="form-control" required>
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
            <div class="mb-3">
                <label class="form-label">Head of Department</label>
                <select name="hod_id" class="form-control" required>
                    <option value="">Select Head of Department</option>
                    <?php
                    $result = $conn->query("SELECT hod_id, person_id FROM head_of_department");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['hod_id']}'>{$row['person_id']}</option>";
                    }
                    ?>
                </select>
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
            <div class="mb-3">
                <label class="form-label">Finance Officer</label>
                <select name="finance_officer_id" class="form-control" required>
                    <option value="">Select Finance Officer</option>
                    <?php
                    $result = $conn->query("SELECT financial_officer_id, first_name FROM finance_officer");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['financial_officer_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Medical Info</label>
                <select name="medical_info_id" class="form-control" required>
                    <option value="">Select Medical Info</option>
                    <?php
                    $result = $conn->query("SELECT medical_info_id, blood_type FROM medical_information");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['medical_info_id']}'>{$row['blood_type']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>