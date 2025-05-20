<?php
session_start();
include '../php/db.php';

// Check if voter is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.html");
    exit();
}

$voter_username = $_SESSION['username'];

// Check if voter has already voted
$sql_check = "SELECT * FROM votes WHERE voter_username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $voter_username);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Voter already voted
    $has_voted = true;
} else {
    $has_voted = false;
}

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];

    // Insert vote record
    $sql_vote = "INSERT INTO votes (voter_username, candidate_id) VALUES (?, ?)";
    $stmt_vote = $conn->prepare($sql_vote);
    $stmt_vote->bind_param("si", $voter_username, $candidate_id);
    $stmt_vote->execute();
    $stmt_vote->close();

    $has_voted = true;
}

// Fetch candidates list
$candidates = [];
$sql = "SELECT * FROM candidates";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cast Your Vote</title>
</head>
<body>
    <h1>Cast Your Vote</h1>

    <?php if ($has_voted): ?>
        <p>You have already voted. Thank you!</p>
        <p><a href="results.php">View Results</a></p>
    <?php else: ?>
        <form method="post">
            <?php foreach ($candidates as $candidate): ?>
                <div>
                    <input type="radio" id="candidate_<?= $candidate['id'] ?>" name="candidate_id" value="<?= $candidate['id'] ?>" required>
                    <label for="candidate_<?= $candidate['id'] ?>">
                        <?= htmlspecialchars($candidate['name']) ?> (<?= htmlspecialchars($candidate['party']) ?>)
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="submit">Vote</button>
        </form>
    <?php endif; ?>

    <p><a href="../logout.php">Logout</a></p>
</body>
</html>
