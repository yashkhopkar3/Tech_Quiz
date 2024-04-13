<?php
// Start session
session_start();

// Check if user is logged in
if(!isset($_SESSION['username'])) {
  echo "Please log in first.";
  exit();
}

// Check if score and category are set
if(!isset($_POST['score']) || !isset($_POST['category'])) {
  echo "Score or category not set.";
  exit();
}

// Database credentials
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "quiz";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement
$sql = "INSERT INTO game_history (username, category, score) VALUES (?, ?, ?)";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $_SESSION['username'], $_POST['category'], $_POST['score']);

// Execute query
if($stmt->execute()) {
  echo "Score inserted successfully.";
} else {
  echo "Error inserting score: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
