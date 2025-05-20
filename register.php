<?php
include 'db.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role']; // 'admin' or 'voter'

$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
   header("Location: ../login.html");
exit();

} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
