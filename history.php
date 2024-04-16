<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game History</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="script.js"></script>
  <style>
    body {
      background-image:url("historybg.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      color: #fff;
      font-family: 'Courier New', Courier, monospace;
    }

    p{
      text-align:center;
      color:black;
      font-size: 30px;
      
    }

    table {
      width: 70%;
      border-collapse: collapse;
      margin-top: 20px;
      margin:auto;
      color: #fff;
      background-color: #222;
    }

    th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #800080; 
  color: white;
}

tr {
  background-color: #CCCCFF; 
  color:black;
}

.icon-container {
  color: black; 
  cursor: pointer;
  font-size: 22px;
  padding-left:5%;
  
}

  </style>
</head>

<body>
  <p><b>Score Card<b></p>
  <?php
    session_start();
    if(!isset($_SESSION['username'])) {
        echo "Please log in first.";
        exit();
      }

    ?>
    <br>
    <div class="icon-container" title="Home" onclick="goToHome()">
    <i class="fas fa-home" id="home-icon" ></i>
    <span class="icon-name">Home</span>
  </div>
  <br>
      <?php
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
    $sql = "SELECT * FROM game_history WHERE username = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['username']);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Display game history
echo "<table>";
echo "<tr><th>Category</th><th>Score</th><th>Timestamp</th></tr>";
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["category"] . "</td><td>" . $row["score"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
  }
} else {
  echo "<tr><td colspan='3'>No game history found.</td></tr>";
}
echo "</table>";

    // Close connection
    $stmt->close();
    $conn->close();
  ?>
  
</body>

</html>
