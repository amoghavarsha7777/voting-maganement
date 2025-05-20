<?php
session_start();

// Check if user is logged in and role is voter
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voter Dashboard</title>
</head>
<body>
    <h1>Welcome, Voter <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p><a href="vote.php">Cast Your Vote</a></p>
    <p><a href="view_results.php">View Results</a></p>
    <p><a href="../logout.php">Logout</a></p>
</body>
</html>
