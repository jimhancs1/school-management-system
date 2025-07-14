<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5 text-center">
        <h1>Welcome to School Management System</h1>
        <p class="lead">Manage your school data efficiently. Select an action below to get started.</p>
        <div class="d-flex justify-content-center">
            <form class="w-50">
                <select class="form-select" onchange="if (this.value) window.location.href = this.value;">
                    <option value="">Select Action</option>
                    <option value="add_student.php">Add Student</option>
                    <?php
                    $tables = [
                        'classes' => 'Classes',
                        'clubs_and_societies' => 'Clubs and Societies',
                        'department' => 'Department',
                        'disciplinary_records' => 'Disciplinary Records',
                        'employment_documents' => 'Employment Documents',
                        'employment_info' => 'Employment Info',
                        'employment_qualifications' => 'Employment Qualifications',
                        'enrollment' => 'Enrollment',
                        'faculty' => 'Faculty',
                        'fees_structure' => 'Fees Structure',
                        'finance_officer' => 'Finance Officer',
                        'financial_details' => 'Financial Details',
                        'grades' => 'Grades',
                        'head_of_department' => 'Head of Department',
                        'medical_information' => 'Medical Information',
                        'next_of_kin' => 'Next of Kin',
                        'sports_involvement' => 'Sports Involvement',
                        'staff_performance_record' => 'Staff Performance Record',
                        'student_club_membership' => 'Student Club Membership',
                        'timetable' => 'Timetable',
                        'unit' => 'Unit',
                        'users' => 'Users',
                        'volunteer_work' => 'Volunteer Work'
                    ];
                    foreach ($tables as $table => $display) {
                        echo "<option value='add_$table.php'>Add $display</option>";
                    }
                    ?>
                    <option value="edit_data.php">Edit Data</option>
                    <option value="data_analysis.php">View Data Analysis</option>
                </select>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>