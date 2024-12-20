<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resterent_orders";

$conn = mysqli_connect($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

// Retrieve form data
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$items = $_POST['items'];
$total = $_POST['total'];
$status= $_POST['status'];

// SQL query to insert data into the orders table
$sql = "INSERT INTO order_details (name, mobile, items, total, Status) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssds", $name, $mobile, $items, $total,$status);

if ($stmt->execute()) {
    echo'<script>
    alert(`Order placed successfully!`);
    window.location.href = `index.html`;
    </script>';
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</body>
</html>