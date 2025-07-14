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
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">School Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Data Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="add_student.php">Add Student</a></li>
                        <?php foreach ($tables as $table => $display): ?>
                            <li><a class="dropdown-item" href="add_<?php echo $table; ?>.php">Add <?php echo htmlspecialchars($display); ?></a></li>
                        <?php endforeach; ?>
                        <li><a class="dropdown-item" href="edit_data.php">Edit Data</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="data_analysis.php">Data Analysis</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>