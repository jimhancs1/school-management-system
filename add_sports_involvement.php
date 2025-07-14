<?php
require 'connect.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $sport_name = $_POST['sport_name'];
    $team_name = $_POST['team_name'];
    $position = $_POST['position'];
    $participation_level = $_POST['participation_level'];
    $participation_year = $_POST['participation_year'];

    $required = ['student_id', 'sport_name'];
    $missing = array_filter($required, fn($field) => empty($$field));
    if ($missing) {
        echo '<div class="alert alert-danger">Please fill all required fields: ' . implode(', ', $missing) . '</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO sports_involvement (student_id, sport_name, team_name, position, participation_level, participation_year) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $student_id, $sport_name, $team_name, $position, $participation_level, $participation_year);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Sports Involvement added successfully!</div>';
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
    <title>Add Sports Involvement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Sports Involvement</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    <?php
                    $result = $conn->query("SELECT student_id, first_name FROM student");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['student_id']}'>{$