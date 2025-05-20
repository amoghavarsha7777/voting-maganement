<?php
include 'db.php';

$user_id = $_POST['user_id'];
$position_id = $_POST['position_id'];
$candidate_id = $_POST['candidate_id'];

// Prevent duplicate voting for the same position
$sql = "SELECT * FROM votes WHERE user_id = ? AND position_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $position_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "You have already voted for this position.";
} else {
    $insert = $conn->prepare("INSERT INTO votes (user_id, candidate_id, position_id) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $candidate_id, $position_id);
    if ($insert->execute()) {
        echo "Vote cast successfully.";
    } else {
        echo "Error: " . $insert->error;
    }
    $insert->close();
}

$stmt->close();
$conn->close();
?>
