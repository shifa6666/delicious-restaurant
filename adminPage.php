<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resterent_orders";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $orderId = $_POST['order_id'];
    $action = $_POST['action'];

    if (in_array($action, ['Accepted', 'Declined', 'Completed'])) {
        $stmt = $conn->prepare("UPDATE order_details SET Status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $action, $orderId);
        $stmt->execute();
        $stmt->close();

        // Set the alert message based on the action using if-else
        if ($action == 'Accepted') {
            echo'<script>alert(`Order has been Accepted!`);</script>';
        } elseif ($action == 'Declined') {
            echo'<script>alert(`Order has been Declined!`);</script>';
        } elseif ($action == 'Completed') {
            echo'<script>alert(`Order has been Completed!`);</script>';
        }
    }
}


// Handle filtering
$statusFilter = isset($_GET['Status']) ? $_GET['Status'] : 'All';
$sql = "SELECT * FROM order_details";
if ($statusFilter !== 'All') {
    $stmt = $conn->prepare("SELECT * FROM order_details WHERE Status = ?");
    $stmt->bind_param("s", $statusFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .btn-accept {
            background-color: green;
            color: white;
        }
        .btn-decline {
            background-color: red;
            color: white;
        }
        .btn-completed {
            background-color: blue;
            color: white;
        }
        select {
            margin-bottom: 20px;
            padding: 5px;
        }
        /* Initially hide actions column */
        .action-column {
            display: none;
        }
        /* Show actions when filter is Pending or Accepted */
        .show-actions .action-column {
            display: table-cell;
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
<button onclick="window.location.href='Home.php';">Home Page</button>
    <h1>Manage Orders</h1>
    <button style="position:absolute;right:10px" onclick="window.location.href = `complaints.php`">Complaints</button>
    <!-- Filter Orders by Status -->
    <form method="GET" action="">
        <label for="Status">Filter by Status:</label>
        <select name="Status" id="Status" onchange="this.form.submit()">
            <option value="All" <?= $statusFilter === 'All' ? 'selected' : '' ?>>All</option>
            <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Accepted" <?= $statusFilter === 'Accepted' ? 'selected' : '' ?>>Accepted</option>
            <option value="Declined" <?= $statusFilter === 'Declined' ? 'selected' : '' ?>>Declined</option>
            <option value="Completed" <?= $statusFilter === 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>
    </form>

    <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Items</th>
                <th>Total</th>
                <th>Time And Date</th>
                <th>Status</th>
                <?php if (in_array($statusFilter, ['Pending', 'Accepted'])): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="<?= in_array($statusFilter, ['Pending', 'Accepted']) ? 'show-actions' : '' ?>">
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td><?= htmlspecialchars($row['items']) ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= htmlspecialchars($row['timeStamp']) ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <?php if (in_array($statusFilter, ['Pending', 'Accepted'])): ?>
                        <td class="action-column">
                            <?php if ($row['Status'] === 'Pending'): ?>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                    <button type="submit" name="action" value="Accepted" class="btn btn-accept">Accept</button>
                                </form>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                    <button type="submit" name="action" value="Declined" class="btn btn-decline">Decline</button>
                                </form>
                            <?php elseif ($row['Status'] === 'Accepted'): ?>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                    <button type="submit" name="action" value="Completed" class="btn btn-completed">Complete</button>
                                </form>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
