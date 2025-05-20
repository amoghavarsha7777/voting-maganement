<?php
$host = '127.0.0.1';
$port = 3307;  // as integer
$user = 'root';
$pass = '';
$dbname = 'voting_system';

// Create connection with port specified
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
