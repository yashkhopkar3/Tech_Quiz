<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body{
            background-color: #fff5bf;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #yellow{
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffe766;
            height: 650px;
            width: 1400px;
            margin: 30px;
        }
        .login-container{
            border: 2px solid black;
            background-color: #fff5bf;
            height: 400px;
            width: 300px;
            position: relative;
            left: 250px;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .login-container:hover {
            transform: scale(1.02);
        }
        h1 {
            text-align: center;
            color: #333;
            font-family: 'Arial', sans-serif;
            font-size: 24px;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type=text], input[type=password] {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type=submit] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            color: white;
            background-color: #1E90FF;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        img {
            width: 50%; /* Added this line to make the image smaller */

        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="yellow">
        <div class="login-container">
            <h1>Login</h1>
            <form action="" method="post">
                Username: <input type="text" name="uname" required><br>
                Password: <input type="password" name="pass" required><br>
                <input type="submit" value="Login">   <input type="submit" value="Register" style="background-color: #45a049;" onclick="location.href='register.php'">
            </form>
        </div>
        <img src="monster_blue.png">
    </div>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database credentials
        $servername = "localhost:3308";
        $username = "root";
        $password = "";
        $dbname = "quiz";

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
            echo "<script type='text/javascript'>alert('Invalid Password.');</script>";
            exit();
          }
        } else {
            echo "<script type='text/javascript'>alert('Invalid User.');</script>";
            exit();
        }
    
        // Close connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<script type='text/javascript'>alert('Please provide both username and password.');</script>";
        exit();
    }
    }
    ?>
    