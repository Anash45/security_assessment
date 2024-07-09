<?php
// Database connection script
require_once 'db_conn.php';

// Admin details
$name = 'Test Admin';
$email = 'admin@gmail.com';
$password = 'asdfasdf';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL insert statement
$sql = "INSERT INTO admin (email, password, name) VALUES (?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters
    $stmt->bind_param("sss", $email, $hashed_password, $name);

    // Execute statement
    if ($stmt->execute()) {
        echo "New admin inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

// Close connection
$conn->close();
?>