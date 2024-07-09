<?php
session_start();
// Database credentials
// $host = "localhost"; // Change this if your database is hosted on a different server
// $username = "uykmnqxbcid6b"; // Change this to your MySQL username
// $password = "7wwcvastx6r1"; // Change this to your MySQL password
// $database = "db5oymqolnfaai"; // Change this to your MySQL database name


$host = "localhost"; // Change this if your database is hosted on a different server
$username = "root"; // Change this to your MySQL username
$password = "root"; // Change this to your MySQL password
$database = "security_assessment_db"; // Change this to your MySQL database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
