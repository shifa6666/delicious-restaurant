<?php
// Database connection settings
$servername = "localhost";
$username = "root";   // Database username
$password = "";       // Database password
$dbname = "resterent_orders";  // Your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the 'contact' table
$sql = "SELECT name, email, response FROM contact";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: 10%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
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
</head>
<body>
    <h1>Complaints/Contact Responses</h1>
    <button onclick="window.location.href='Home.php';">Home Page</button>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['response']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No complaints to display.</p>
    <?php endif; ?>
</body>
</html>
