
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../php/db.php';

// Only allow admin or voter
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'voter'])) {
    header("Location: ../login.html");
    exit();
}

$sql = "
 SELECT 
    candidates.name AS candidate_name,
    candidates.party,
    positions.name AS position_name,
    COUNT(votes.id) AS total_votes
FROM candidates
LEFT JOIN votes ON candidates.id = votes.candidate_id
JOIN positions ON candidates.position_id = positions.id
GROUP BY candidates.id
ORDER BY positions.name ASC, total_votes DESC

";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
</head>
<body>
    <h1>Election Results</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Position</th>
                <th>Candidate</th>
                <th>Party</th>
                <th>Total Votes</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['position_name']) ?></td>
                    <td><?= htmlspecialchars($row['candidate_name']) ?></td>
                    <td><?= htmlspecialchars($row['party']) ?></td>
                    <td><?= $row['total_votes'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No votes recorded yet.</p>
    <?php endif; ?>

    <h1><a href="../logout.php">Logout</a></h1>
</body>
</html>
