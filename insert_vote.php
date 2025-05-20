<?php
include 'php/db.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$candidate_id = $_POST['candidate_id']; 

    // Example values, you should replace with real session/user values
    $user_id = 1; // Replace with: $_SESSION['user_id'];
    $username = 'voter1'; // Replace with: $_SESSION['username'];
    $position_id = 1; // Optional: from hidden input or lookup

    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO votes (user_id, candidate_id, position_id, created_at, voter_username)
              VALUES ('$user_id', '$candidate_id', '$position_id', '$created_at', '$username')";

    if (mysqli_query($conn, $query)) {
        echo "Vote recorded successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
