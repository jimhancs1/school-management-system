<?php
$servername = "localhost";
$username = "school_admin"; // Replace with your MySQL username
$password = "cAqrXpN/6tyh0reS"; // Replace with your MySQL password
$dbname = "sms-4.0";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>