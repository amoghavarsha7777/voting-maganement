<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../php/db.php';

// Check if voter is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'voter') {
    header("Location: ../login.html");
    exit();
}

$voter_username = $_SESSION['username'];

// Check if voter has already voted
// Check if voter has already voted
$sql_check = "SELECT * FROM votes WHERE voter_username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $voter_username);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $has_voted = true;
} else {
    $has_voted = false;
}

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];

    // Get position_id for candidate
    $stmt_pos = $conn->prepare("SELECT position_id FROM candidates WHERE id = ?");
    $stmt_pos->bind_param("i", $candidate_id);
    $stmt_pos->execute();
    $result_pos = $stmt_pos->get_result();
    $row_pos = $result_pos->fetch_assoc();
    $position_id = $row_pos['position_id'];
    $stmt_pos->close();

    // Insert vote with position_id
    $stmt_vote = $conn->prepare("INSERT INTO votes (voter_username, candidate_id, position_id) VALUES (?, ?, ?)");
$stmt_vote->bind_param("sii", $voter_username, $candidate_id, $position_id);
$stmt_vote->execute();

    $stmt_vote->close();

    $has_voted = true;
}



// Fetch candidates list
$candidates = [];
$sql ="SELECT candidates.id, candidates.name, candidates.party, candidates.position_id, positions.name AS position_name FROM candidates JOIN positions ON candidates.position_id = positions.id";
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
    <div style="text-align: center; margin-top: 50px;">
    <h1>Cast Your Vote</h1>

    <?php if ($has_voted): ?>
        <h1>You have already voted. Thank you!</h1>
        <h1><a href="result.php">View Results</a></h1>
    <?php else: ?>
       <div style="display: flex; justify-content: center; margin-top: 40px;">
    <form method="post">
        <table border="1" cellpadding="10" cellspacing="0" style="text-align: left; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidates as $candidate): ?>
                <tr>
                    <td>
                        <input type="radio" id="candidate_<?= $candidate['id'] ?>" name="candidate_id" value="<?= $candidate['id'] ?>" required>
                    </td>
                    <td><?= htmlspecialchars($candidate['name']) ?></td>
                    <td><?= htmlspecialchars($candidate['party']) ?></td>
                    <td><?= htmlspecialchars($candidate['position_name']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <button type="submit">Vote</button>
        </div>
        </div>
    </form>
</div>

    <?php endif; ?>

    <h1><a href="../logout.php">Logout</a></h1>
    
</body>
</html>