<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $degrees_aquired = $_POST['degrees_aquired'];
    $professional_certificates = $_POST['professional_certificates'];
    $specialization = $_POST['specialization'];
    $institutions_atended = $_POST['institutions_atended'];
    $years_of_experience = $_POST['years_of_experience'];
    $languages_spoken = $_POST['languages_spoken'];
    $staff_id = $_POST['staff_id'];

    $required = ['staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO employment_qualifications (degrees_aquired, professional_certificates, specialization, institutions_atended, years_of_experience, languages_spoken, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $degrees_aquired, $professional_certificates, $specialization, $institutions_atended, $years_of_experience, $languages_spoken, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Employment Qualifications added successfully!</div>';
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
    <title>Add Employment Qualifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Employment Qualifications</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Degrees Aquired</label>
                <input type="text" name="degrees_aquired" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Professional Certificates</label>
                <input type="text" name="professional_certificates" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Specialization</label>
                <input type="text" name="specialization" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Institutions Atended</label>
                <input type="text" name="institutions_atended" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Years of Experience</label>
                <input type="text" name="years_of_experience" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Languages Spoken</label>
                <input type="text" name="languages_spoken" class="form-control">
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
            <button type="submit" class="btn btn-primary">Add Employment Qualifications</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>