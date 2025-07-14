<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $semester_id = $_POST['semester_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $required = ['day_of_week', 'start_time', 'end_time'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO timetable (class_id, semester_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $class_id, $semester_id, $day_of_week, $start_time, $end_time);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Timetable added successfully!</div>';
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
    <title>Add Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Timetable</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-control">
                    <option value="">Select Class</option>
                    <?php
                    $result = $conn->query("SELECT class_id, room FROM classes");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['class_id']}'>{$row['room']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-control">
                    <option value="">Select Semester</option>
                    <?php
                    $result = $conn->query("SELECT semester_id, semester_name FROM semester");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['semester_id']}'>{$row['semester_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Day of Week</label>
                <select name="day_of_week" class="form-control" required>
                    <option value="">Select Day of Week</option>
                    <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $value): ?>
                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">End Time</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Timetable</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>