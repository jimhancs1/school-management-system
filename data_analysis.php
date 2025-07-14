<?php
include 'navbar.php';
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analysis - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Data Analysis</h1>

        <!-- Table 1: Student, Course, Unit -->
        <h2>Student Course and Unit Enrollment</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql1 = "
                    SELECT 
                        CONCAT(s.first_name, ' ', s.last_name) AS student_name,
                        c.course_name,
                        u.unit_name
                    FROM student s
                    LEFT JOIN enrollment e ON s.student_id = e.student_id
                    LEFT JOIN classes cl ON e.class_id = cl.class_id
                    LEFT JOIN course c ON cl.course_id = c.course_id
                    LEFT JOIN unit_membership um ON s.student_id = um.`student-id`
                    LEFT JOIN unit u ON um.unit_id = u.unit_id
                ";
                $result1 = $conn->query($sql1);
                
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['course_name'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['unit_name'] ?? 'N/A') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Table 2: Staff, Course ID, Course Name, Unit ID, Unit Name -->
        <h2>Staff Course and Unit Assignments</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Staff First Name</th>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Unit ID</th>
                    <th>Unit Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql2 = "
                    SELECT 
                        s.staff_first_name,
                        c.course_id,
                        c.course_name,
                        u.unit_id,
                        u.unit_name
                    FROM staff s
                    LEFT JOIN unit_membership um ON s.staff_id = um.staff_id
                    LEFT JOIN unit u ON um.unit_id = u.unit_id
                    LEFT JOIN classes cl ON u.unit_id = cl.unit_id
                    LEFT JOIN course c ON cl.course_id = c.course_id
                ";
                $result2 = $conn->query($sql2);
                
                if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['staff_first_name'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['course_id'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['course_name'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['unit_id'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['unit_name'] ?? 'N/A') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>