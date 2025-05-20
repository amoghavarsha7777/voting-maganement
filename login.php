<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL to get user by username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.html");
            exit();
        } else if ($user['role'] === 'voter') {
            header("Location: ../voter/vote.php");
            exit();
        }
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
