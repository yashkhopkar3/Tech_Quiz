<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #d0efff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 350px;
            height: auto;
            padding: 16px;
            background-color: white;
            border: 1px solid black;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin-right: 20px; /* Added this line to create space between the image and the container */
        }
        .container:hover {
            transform: scale(1.02);
        }
        h1 {
            text-align: center;
            color: #333;
            font-family: 'Arial', sans-serif;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type=text], input[type=password] {
            width: 90%;
            padding: 15px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #f1f1f1;
            border-radius: 5px;
        }
        button {
            background-color: 
            #2a9df4;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            opacity:1;
            background-color: #45a049;
        }
        img{
            padding-top: 50px;
            width: 25%;
            margin-right: 2px; /* Added this line to create space between the image and the container */
        }
    </style>
</head>
<body>
    <img src="model_back.png">
    <div class="container">
        <h1>Create Account?</h1>
        <form method="POST" action="">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <label for="psw-confirm"><b>Confirm Password</b></label>
            <input type="password" placeholder="Confirm Password" name="psw-confirm" required>
            <button type="submit" onclick="check()">Register</button>
        </form>        
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

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the submitted data
        $username = $_POST['uname'];
        $password = $_POST['psw'];
        $confirm_password = $_POST['psw-confirm'];

        // Check if the passwords match
        if($password != $confirm_password) {
            echo "<script type='text/javascript'>alert('Passwords do not match.');</script>";
            exit;
        }

        // Insert the data into the database
        $sql = "INSERT INTO user(username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Account created successfully.');window.location.href = 'index.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
?>

