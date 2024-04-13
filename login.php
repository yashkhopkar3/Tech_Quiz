<?php
// Retrieve user input
if(isset($_POST['uname']) && isset($_POST['pass'])) {
    $name = $_POST['uname'];
    $pass = $_POST['pass'];

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
    $sql = "SELECT * FROM user WHERE username = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    // Execute query
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows != 0) {
      // Fetch row
      $row = $result->fetch_assoc();

      // Verify password
      if($row && $pass == $row['password']) {
        // Start session to store username
        session_start();
        $_SESSION['username'] = $name;
        // Redirect to home.html
        header("Location: home.html");
        exit();
      } else {
        echo "Invalid Password";
      }
    } else {
      echo "Invalid User";
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo "Please provide both username and password.";
}
?>
