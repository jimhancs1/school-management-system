<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_feedback = $_POST['student_feedback'];
    $peer_feedback = $_POST['peer_feedback'];
    $disciplinary_record = $_POST['disciplinary_record'];
    $promotions = $_POST['promotions'];
    $transfers = $_POST['transfers'];
    $leave_record = $_POST['leave_record'];
    $training_workshop_attendance = $_POST['training_workshop_attendance'];
    $staff_id = $_POST['staff_id'];

    $required = ['staff_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO staff_performance_record (student_feedback, peer_feedback, disciplinary_record, promotions, transfers, leave_record, training_workshop_attendance, staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $student_feedback, $peer_feedback, $disciplinary_record, $promotions, $transfers, $leave_record, $training_workshop_attendance, $staff_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Staff Performance Record added successfully!</div>';
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
    <title>Add Staff Performance Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Staff Performance Record</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Student Feedback</label>
                <input type="text" name="student_feedback" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Peer Feedback</label>
                <input type="text" name="peer_feedback" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Disciplinary Record</label>
                <input type="text" name="disciplinary_record" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Promotions</label>
                <input type="text" name="promotions" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Transfers</label>
                <input type="text" name="transfers" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Leave Record</label>
                <input type="text" name="leave_record" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Training Workshop Attendance</label>
                <input type="text" name="training_workshop_attendance" class="form-control">
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
            <button type="submit" class="btn btn-primary">Add Staff Performance Record</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>