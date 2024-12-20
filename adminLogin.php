<?php
// Database connection settings
$servername = "localhost";
$username = "root";   // Database username
$password = "";       // Database password
$dbname = "resterent_orders";  // Your database name

// Initialize error message
$errorMessage = "";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $enteredUsername = $_POST['admindetail'];
    $enteredPassword = $_POST['adminpass'];

    // Debugging - Output entered values
    // echo "Entered Username: " . htmlspecialchars($enteredUsername) . "<br>";
    // echo "Entered Password: " . htmlspecialchars($enteredPassword) . "<br>";

    // Query to check if the entered username exists
    $sql = "SELECT * FROM admin WHERE name = ?";  // Assuming 'admin' is the table storing admin data
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enteredUsername); // 's' means the variable is a string
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If user exists, validate password
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Debugging - Output the stored password hash
        //echo "Stored Password Hash: " . $row['password'] . "<br>";

        // If password is stored as plain text
        if ($enteredPassword === $row['password']) {
            // If password matches, redirect to admin page
            echo '<script>
            alert(`Welcome Back Admin..!`);
            window.location.href = `adminPage.php`;
            </script>';
            exit(); // Ensure no further code is executed
        } 
        // If password is hashed
        elseif (password_verify($enteredPassword, $row['password'])) {
            // If password is hashed and matches
            header('Location: adminPage.php');
            exit();
        } else {
            // If password does not match
            $errorMessage = "Invalid password, please try again.";
        }
    } else {
        // If username does not exist
        $errorMessage = "Invalid username, please try again.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<style>
     .home-button {
            position: absolute; /* Positioning the button */
            top: 10px; /* Distance from the top */
            right: 10px; /* Distance from the right */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            border: none; /* Remove borders */
            padding: 10px 20px; /* Padding for button size */
            font-size: 16px; /* Font size */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Add shadow */
        }

        /* Button hover effect */
        .home-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
</style>
<body>
    <button onclick="window.location.href='Home.php';">Home Page</button>
    <form method="POST">
        <center>
            <h2>Admin Login</h2>
            <input type="text" name="admindetail" placeholder="Enter Your Name or Email" required><br><br>
            <input type="password" name="adminpass" placeholder="Enter Your Password" required><br><br>
            <input type="submit" value="Login">
        </center>
    </form>

    <?php
    // Display error message if credentials are invalid
    if (!empty($errorMessage)) {
        echo "<center><p style='color:red;'>$errorMessage</p></center>";
    }
    ?>
</body>
</html>
