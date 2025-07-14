<?php
require 'connect.php';
include 'navbar.php';

// List of tables
$tables = [
    'classes', 'clubs_and_societies', 'department', 'disciplinary_records', 'employment_documents',
    'employment_info', 'employment_qualifications', 'enrollment', 'faculty', 'fees_structure',
    'finance_officer', 'financial_details', 'grades', 'head_of_department', 'medical_information',
    'next_of_kin', 'sports_involvement', 'staff_performance_record', 'student_club_membership',
    'timetable', 'unit', 'users', 'volunteer_work', 'data_analysis' 
];

// Table schema details (fields, types, foreign keys, required fields)
$table_details = [
    'classes' => [
        'primary_key' => 'class_id',
        'fields' => ['course_id' => 'int', 'professor_id' => 'int', 'room' => 'text', 'student_capacity' => 'text', 'status' => 'text'],
        'foreign_keys' => [
            'course_id' => ['table' => 'course', 'key' => 'course_id', 'display' => 'course_name'],
            'professor_id' => ['table' => 'professor', 'key' => 'professor_id', 'display' => 'first_name']
        ],
        'required' => [],
        'bind_types' => 'iisss'
    ],
    'clubs_and_societies' => [
        'primary_key' => 'club_id',
        'fields' => ['club_name' => 'text', 'description' => 'textarea', 'established_year' => 'number', 'contact_email' => 'text', 'student_id' => 'int'],
        'foreign_keys' => ['student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name']],
        'required' => ['club_name', 'student_id'],
        'bind_types' => 'sssis'
    ],
    'department' => [
        'primary_key' => 'department_id',
        'fields' => ['department_name' => 'text', 'faculty_id' => 'int', 'head_of_department_id' => 'int', 'department_abbreviation' => 'text', 'office_location' => 'text', 'email_address' => 'text', 'established_year' => 'text', 'phone_number' => 'text'],
        'foreign_keys' => [
            'faculty_id' => ['table' => 'faculty', 'key' => 'faculty_id', 'display' => 'faculty_name'],
            'head_of_department_id' => ['table' => 'head_of_department', 'key' => 'hod_id', 'display' => 'person_id']
        ],
        'required' => ['department_name', 'head_of_department_id'],
        'bind_types' => 'siisssss'
    ],
    'disciplinary_records' => [
        'primary_key' => 'record_id',
        'fields' => ['student_id' => 'int', 'incident_date' => 'date', 'incident_description' => 'textarea', 'action_taken' => 'textarea', 'recorded_by' => 'number', 'staff_id' => 'int'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['student_id', 'incident_date', 'staff_id'],
        'bind_types' => 'isssii'
    ],
    'employment_documents' => [
        'primary_key' => 'document_id',
        'fields' => ['staff_id' => 'int', 'resume' => 'text', 'certificates' => 'text', 'national_id' => 'text', 'acceptance_letter' => 'text', 'recommendation_letter' => 'text', 'professional_liscence' => 'text', 'police_verification' => 'text', 'experience_letter' => 'text'],
        'foreign_keys' => ['staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']],
        'required' => ['staff_id', 'professional_liscence'],
        'bind_types' => 'issssssss'
    ],
    'employment_info' => [
        'primary_key' => 'employment_id',
        'fields' => ['department_id' => 'int', 'job_title' => 'text', 'employment_type' => 'text', 'hire_date' => 'date', 'employee_status' => 'text', 'supervisor' => 'text', 'staff_category' => 'text', 'employment_role' => 'text', 'office_location' => 'text', 'office_number' => 'text', 'work_hours' => 'text', 'staff_id' => 'int'],
        'foreign_keys' => [
            'department_id' => ['table' => 'department', 'key' => 'department_id', 'display' => 'department_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['staff_id'],
        'bind_types' => 'issssssssssi'
    ],
    'employment_qualifications' => [
        'primary_key' => 'qualification_id',
        'fields' => ['degrees_aquired' => 'text', 'professional_certificates' => 'text', 'specialization' => 'text', 'institutions_atended' => 'text', 'years_of_experience' => 'text', 'languages_spoken' => 'text', 'staff_id' => 'int'],
        'foreign_keys' => ['staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']],
        'required' => ['staff_id'],
        'bind_types' => 'ssssssi'
    ],
    'enrollment' => [
        'primary_key' => 'enrollment_id',
        'fields' => ['student_id' => 'int', 'class_id' => 'int', 'enrollment_date' => 'date', 'enrollment_status' => 'text'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'class_id' => ['table' => 'classes', 'key' => 'class_id', 'display' => 'room']
        ],
        'required' => ['enrollment_date', 'enrollment_status'],
        'bind_types' => 'iiss'
    ],
    'faculty' => [
        'primary_key' => 'faculty_id',
        'fields' => ['faculty_name' => 'text', 'faculty_abbreviation' => 'text', 'establishment_date' => 'date', 'accreditation_status' => 'text', 'location' => 'text', 'phone_number' => 'text', 'email_address' => 'text', 'dean_id' => 'int'],
        'foreign_keys' => ['dean_id' => ['table' => 'dean', 'key' => 'dean_id', 'display' => 'first_name']],
        'required' => ['faculty_name', 'dean_id'],
        'bind_types' => 'sssssssi'
    ],
    'fees_structure' => [
        'primary_key' => 'fee_id',
        'fields' => ['department_id' => 'int', 'semester_id' => 'int', 'amount' => 'text', 'description' => 'text'],
        'foreign_keys' => [
            'department_id' => ['table' => 'department', 'key' => 'department_id', 'display' => 'department_name'],
            'semester_id' => ['table' => 'semester', 'key' => 'semester_id', 'display' => 'semester_name']
        ],
        'required' => ['amount'],
        'bind_types' => 'iiss'
    ],
    'finance_officer' => [
        'primary_key' => 'officer_id',
        'fields' => ['first_name' => 'text', 'last_name' => 'text', 'hire_date' => 'date', 'staff_id' => 'int'],
        'foreign_keys' => ['staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']],
        'required' => ['first_name', 'last_name', 'hire_date', 'staff_id'],
        'bind_types' => 'sssi'
    ],
    'financial_details' => [
        'primary_key' => 'financial_id',
        'fields' => ['student_id' => 'int', 'staff_id' => 'int', 'fi_name' => 'text', 'fi_number' => 'text', 'fi_location' => 'text', 'finance_method' => 'number'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['student_id', 'staff_id', 'fi_name', 'fi_number', 'finance_method'],
        'bind_types' => 'iissii'
    ],
    'grades' => [
        'primary_key' => 'grade_id',
        'fields' => ['student_id' => 'int', 'unit_id' => 'int', 'grade' => 'text', 'semester_id' => 'int', 'graded_by' => 'text', 'grade_date' => 'text', 'professor_id' => 'int'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'unit_id' => ['table' => 'unit', 'key' => 'unit_id', 'display' => 'unit_name'],
            'semester_id' => ['table' => 'semester', 'key' => 'semester_id', 'display' => 'semester_name'],
            'professor_id' => ['table' => 'professor', 'key' => 'professor_id', 'display' => 'first_name']
        ],
        'required' => ['grade', 'graded_by', 'grade_date', 'professor_id'],
        'bind_types' => 'iisisii'
    ],
    'head_of_department' => [
        'primary_key' => 'hod_id',
        'fields' => ['department_id' => 'int', 'person_id' => 'number', 'start_date' => 'date', 'end_date' => 'date', 'remarks' => 'textarea', 'staff_id' => 'int', 'contact_info_id' => 'int'],
        'foreign_keys' => [
            'department_id' => ['table' => 'department', 'key' => 'department_id', 'display' => 'department_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name'],
            'contact_info_id' => ['table' => 'contact_info', 'key' => 'contact_info_id', 'display' => 'personal_email']
        ],
        'required' => ['department_id', 'person_id', 'start_date', 'staff_id', 'contact_info_id'],
        'bind_types' => 'iissiii'
    ],
    'medical_information' => [
        'primary_key' => 'medical_id',
        'fields' => ['student_id' => 'int', 'hospital_name' => 'text', 'doctor_name' => 'text', 'blood_type' => 'text', 'known_allergies' => 'text', 'medical_conditions' => 'text', 'immuniaztion_record' => 'text', 'health_insurance_name' => 'text', 'staff_id' => 'int'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['student_id', 'blood_type', 'staff_id'],
        'bind_types' => 'isssssssi'
    ],
    'next_of_kin' => [
        'primary_key' => 'kin_id',
        'fields' => ['first_name' => 'text', 'last_name' => 'text', 'date_of_birth' => 'date', 'gender' => 'text', 'nationality' => 'text', 'religion' => 'text', 'marital_status' => 'text', 'relationship' => 'text', 'contact_info_id' => 'int', 'student_id' => 'int', 'staff_id' => 'int', 'language' => 'text', 'Occupation' => 'number'],
        'foreign_keys' => [
            'contact_info_id' => ['table' => 'contact_info', 'key' => 'contact_info_id', 'display' => 'personal_email'],
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['contact_info_id', 'Occupation'],
        'bind_types' => 'sssssssiiiiii'
    ],
    'sports_involvement' => [
        'primary_key' => 'involvement_id',
        'fields' => ['student_id' => 'int', 'sport_name' => 'text', 'team_name' => 'text', 'position' => 'text', 'participation_level' => 'select', 'participation_year' => 'number'],
        'foreign_keys' => ['student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name']],
        'required' => ['student_id', 'sport_name'],
        'bind_types' => 'issssi'
    ],
    'staff_performance_record' => [
        'primary_key' => 'record_id',
        'fields' => ['student_feedback' => 'text', 'peer_feedback' => 'text', 'disciplinary_record' => 'text', 'promotions' => 'text', 'transfers' => 'text', 'leave_record' => 'text', 'training_workshop_attendance' => 'text', 'staff_id' => 'int'],
        'foreign_keys' => ['staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']],
        'required' => ['staff_id'],
        'bind_types' => 'sssssssi'
    ],
    'student_club_membership' => [
        'primary_key' => 'membership_id',
        'fields' => ['student_id' => 'int', 'club_id' => 'int', 'role' => 'text', 'join_date' => 'date'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'club_id' => ['table' => 'clubs_and_societies', 'key' => 'club_id', 'display' => 'club_name']
        ],
        'required' => ['student_id', 'club_id'],
        'bind_types' => 'iiss'
    ],
    'timetable' => [
        'primary_key' => 'timetable_id',
        'fields' => ['class_id' => 'int', 'semester_id' => 'int', 'day_of_week' => 'select', 'start_time' => 'time', 'end_time' => 'time'],
        'foreign_keys' => [
            'class_id' => ['table' => 'classes', 'key' => 'class_id', 'display' => 'room'],
            'semester_id' => ['table' => 'semester', 'key' => 'semester_id', 'display' => 'semester_name']
        ],
        'required' => ['day_of_week', 'start_time', 'end_time'],
        'bind_types' => 'iisss'
    ],
    'unit' => [
        'primary_key' => 'unit_id',
        'fields' => ['unit_code' => 'text', 'unit_name' => 'text', 'course_id' => 'int', 'professor_id' => 'int', 'unit_status' => 'text', 'unit_description' => 'text'],
        'foreign_keys' => [
            'course_id' => ['table' => 'course', 'key' => 'course_id', 'display' => 'course_name'],
            'professor_id' => ['table' => 'professor', 'key' => 'professor_id', 'display' => 'first_name']
        ],
        'required' => ['unit_code', 'unit_name', 'unit_status', 'unit_description'],
        'bind_types' => 'ssiiss'
    ],
    'users' => [
        'primary_key' => 'user_id',
        'fields' => ['username' => 'text', 'password' => 'text', 'role' => 'select'],
        'foreign_keys' => [],
        'required' => ['username', 'password', 'role'],
        'bind_types' => 'sss'
    ],
    'volunteer_work' => [
        'primary_key' => 'volunteer_id',
        'fields' => ['student_id' => 'int', 'organization_name' => 'text', 'role' => 'text', 'start_date' => 'date', 'end_date' => 'date', 'description' => 'textarea', 'staff_id' => 'int'],
        'foreign_keys' => [
            'student_id' => ['table' => 'student', 'key' => 'student_id', 'display' => 'first_name'],
            'staff_id' => ['table' => 'staff', 'key' => 'staff_id', 'display' => 'first_name']
        ],
        'required' => ['student_id', 'staff_id'],
        'bind_types' => 'isssssi'
    ]
];

// Select options for specific fields
$select_options = [
    'sports_involvement' => ['participation_level' => ['School', 'Regional', 'National', 'International']],
    'timetable' => ['day_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']],
    'users' => ['role' => ['admin', 'professor', 'student']]
];

// Handle table selection
$selected_table = isset($_GET['table']) && in_array($_GET['table'], $tables) ? $_GET['table'] : '';
$record_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$record = null;

// Fetch record for editing
if ($selected_table && $record_id && $action == 'edit') {
    $stmt = $conn->prepare("SELECT * FROM `$selected_table` WHERE {$table_details[$selected_table]['primary_key']} = ?");
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
}

// Handle edit submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $selected_table && $record_id && $action == 'edit') {
    $fields = $table_details[$selected_table]['fields'];
    $values = [];
    $bind_params = $table_details[$selected_table]['bind_types'];
    $params = [];
    
    foreach ($fields as $field => $type) {
        $value = $_POST[$field] ?? '';
        $values[] = $value;
        $params[] = &$values[count($values) - 1];
    }
    
    $values[] = $record_id;
    $params[] = &$values[count($values) - 1];
    $bind_params .= 'i';
    
    $required = $table_details[$selected_table]['required'];
    $missing = array_filter($required, fn($field) => empty($_POST[$field]));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $set_clause = implode(', ', array_map(fn($f) => "$f = ?", array_keys($fields)));
        $stmt = $conn->prepare("UPDATE `$selected_table` SET $set_clause WHERE {$table_details[$selected_table]['primary_key']} = ?");
        $stmt->bind_param($bind_params, ...$params);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Record updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Handle delete submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $selected_table && $record_id && $action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM `$selected_table` WHERE {$table_details[$selected_table]['primary_key']} = ?");
    $stmt->bind_param("i", $record_id);
    
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Record deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit or Delete Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit or Delete Data</h2>
        
        <!-- Table Selection -->
        <form method="GET" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Select Table</label>
                <select name="table" class="form-control" onchange="this.form.submit()">
                    <option value="">Select Table</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?php echo $table; ?>" <?php echo $selected_table == $table ? 'selected' : ''; ?>>
                            <?php echo ucwords(str_replace('_', ' ', $table)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($selected_table): ?>
            <!-- Records List -->
            <h3>Records in <?php echo ucwords(str_replace('_', ' ', $selected_table)); ?></h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php foreach ($table_details[$selected_table]['fields'] as $field => $type): ?>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM `$selected_table`");
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <?php foreach ($table_details[$selected_table]['fields'] as $field => $type): ?>
                                <td><?php echo htmlspecialchars($row[$field] ?? ''); ?></td>
                            <?php endforeach; ?>
                            <td>
                                <a href="?table=<?php echo $selected_table; ?>&action=edit&id=<?php echo $row[$table_details[$selected_table]['primary_key']]; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="?table=<?php echo $selected_table; ?>&action=delete&id=<?php echo $row[$table_details[$selected_table]['primary_key']]; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php if ($action == 'edit' && $record): ?>
                <!-- Edit Form -->
                <h3>Edit Record</h3>
                <form method="POST">
                    <?php foreach ($table_details[$selected_table]['fields'] as $field => $type): ?>
                        <div class="mb-3">
                            <label class="form-label"><?php echo ucwords(str_replace('_', ' ', $field)); ?></label>
                            <?php if (isset($table_details[$selected_table]['foreign_keys'][$field])): ?>
                                <select name="<?php echo $field; ?>" class="form-control" <?php echo in_array($field, $table_details[$selected_table]['required']) ? 'required' : ''; ?>>
                                    <option value="">Select <?php echo ucwords(str_replace('_', ' ', $field)); ?></option>
                                    <?php
                                    $fk = $table_details[$selected_table]['foreign_keys'][$field];
                                    $result = $conn->query("SELECT {$fk['key']}, {$fk['display']} FROM {$fk['table']}");
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = $row[$fk['key']] == $record[$field] ? 'selected' : '';
                                        echo "<option value='{$row[$fk['key']]}' $selected>{$row[$fk['display']]}</option>";
                                    }
                                    ?>
                                </select>
                            <?php elseif ($type == 'textarea'): ?>
                                <textarea name="<?php echo $field; ?>" class="form-control" <?php echo in_array($field, $table_details[$selected_table]['required']) ? 'required' : ''; ?>><?php echo htmlspecialchars($record[$field] ?? ''); ?></textarea>
                            <?php elseif ($type == 'select' && isset($select_options[$selected_table][$field])): ?>
                                <select name="<?php echo $field; ?>" class="form-control" <?php echo in_array($field, $table_details[$selected_table]['required']) ? 'required' : ''; ?>>
                                    <option value="">Select <?php echo ucwords(str_replace('_', ' ', $field)); ?></option>
                                    <?php foreach ($select_options[$selected_table][$field] as $option): ?>
                                        <option value="<?php echo $option; ?>" <?php echo $record[$field] == $option ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?php echo $type == 'date' ? 'date' : ($type == 'number' ? 'number' : ($type == 'time' ? 'time' : 'text')); ?>" 
                                       name="<?php echo $field; ?>" 
                                       class="form-control" 
                                       value="<?php echo htmlspecialchars($record[$field] ?? ''); ?>" 
                                       <?php echo in_array($field, $table_details[$selected_table]['required']) ? 'required' : ''; ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Update Record</button>
                    <a href="?table=<?php echo $selected_table; ?>" class="btn btn-secondary">Cancel</a>
                </form>
            <?php elseif ($action == 'delete' && $record): ?>
                <!-- Delete Confirmation -->
                <h3>Delete Record</h3>
                <div class="alert alert-warning">
                    Are you sure you want to delete this record? This action cannot be undone.
                </div>
                <form method="POST">
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    <a href="?table=<?php echo $selected_table; ?>" class="btn btn-secondary">Cancel</a>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>