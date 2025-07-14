<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $course_id = isset($_POST['course_id']) && $_POST['course_id'] !== '' ? (int)$_POST['course_id'] : null;
    $room = isset($_POST['room']) ? trim($_POST['room']) : '';
    $student_capacity = isset($_POST['student_capacity']) && $_POST['student_capacity'] !== '' ? (int)$_POST['student_capacity'] : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    // Debug: Output submitted values (remove after testing)
    /*
    echo '<pre>';
    var_dump([
        'course_id' => $course_id,
        'room' => $room,
        'student_capacity' => $student_capacity,
        'status' => $status
    ]);
    echo '</pre>';
    */

    // Required fields validation
    $missing = [];
    if ($course_id === null || $course_id === 0) $missing[] = 'course_id';
    if (empty($room)) $missing[] = 'room';
    if ($student_capacity === null || $student_capacity === 0) $missing[] = 'student_capacity';
    if (empty($status)) $missing[] = 'status';

    if ($missing) {
        echo '<div class="alert alert-danger">Error: Missing required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        // Validate course_id exists in course table
        $stmt = $conn->prepare("SELECT course_id FROM course WHERE course_id = ?");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo '<div class="alert alert-danger">Invalid course selected.</div>';
            $stmt->close();
        } else {
            $stmt->close();

            // Insert into classes table
            $stmt = $conn->prepare("INSERT INTO classes (course_id, room, student_capacity, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isis", $course_id, $room, $student_capacity, $status);

            if ($stmt->execute()) {
                echo '<div class="alert alert-success">Class added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Classes</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-control">
                    <option value="">Select Course</option>
                    <?php
                    $result = $conn->query("SELECT course_id, course_name FROM course");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['course_id']}'>" . htmlspecialchars($row['course_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Room</label>
                <input type="text" name="room" class="form-control" value="<?php echo isset($_POST['room']) ? htmlspecialchars($_POST['room']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Student Capacity</label>
                <input type="number" name="student_capacity" class="form-control" value="<?php echo isset($_POST['student_capacity']) ? htmlspecialchars($_POST['student_capacity']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="<?php echo isset($_POST['status']) ? htmlspecialchars($_POST['status']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Add Classes</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>