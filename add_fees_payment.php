<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $fees_structure_id = $_POST['fees_structure_id'];
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];
    $payment_status = $_POST['payment_status'];
    $semester_id = $_POST['semester_id'];

    $required = ['payment_date', 'amount_paid', 'payment_status', 'semester_id'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO fees_payment (student_id, fees_structure_id, payment_date, amount_paid, payment_status, semester_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisdsi", $student_id, $fees_structure_id, $payment_date, $amount_paid, $payment_status, $semester_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Fees Payment added successfully!</div>';
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
    <title>Add Fees Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Fees Payment</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-control">
                    <option value="">Select Student</option>
                    <?php
                    $result = $conn->query("SELECT student_id, first_name FROM student");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['student_id']}'>{$row['first_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fees Structure</label>
                <select name="fees_structure_id" class="form-control">
                    <option value="">Select Fees Structure</option>
                    <?php
                    $result = $conn->query("SELECT fees_structure_id, description FROM fees_structure");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['fees_structure_id']}'>{$row['description']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Date</label>
                <input type="date" name="payment_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Status</label>
                <select name="payment_status" class="form-control" required>
                    <option value="">Select Payment Status</option>
                    <?php foreach (['Paid', 'Pending', 'Partial'] as $value): ?>
                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester_id" class="form-control" required>
                    <option value="">Select Semester</option>
                    <?php
                    $result = $conn->query("SELECT semester_id, semester_name FROM semester");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['semester_id']}'>{$row['semester_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Fees Payment</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>