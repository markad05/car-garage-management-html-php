<?php
session_start();
if(!isset($_SESSION['userr'])) { header("location: login.php");} 
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT model, license_plate, code, owner_name, phone_number, issue_details, total_cost, entry_date, completion_date, notes FROM old_cars ORDER BY completion_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Previous Cars</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; height: 100vh; overflow: hidden; }

        .sidebar {
            width: 200px;
            background: #2c3e50;
            color: white;
            padding: 20px 10px;
            flex-shrink: 0;
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            padding: 8px;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 200px;
            right: 0;
            height: 60px;
            background: #1abc9c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            z-index: 10;
        }

        .main {
            flex: 1;
            overflow-y: auto;
            background: #f5f5f5;
            padding: 100px 20px 40px 20px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #1abc9c;
            color: white;
        }


    </style>
</head>
<body>

<div class="sidebar">
    <h2>Menu</h2>
    <a href="homepage.php">Home</a>
    <a href="inventory.php">Inventory</a>
    <a href="addcar.php">Add Car</a>
    <a href="finishcar.php">Finish Car</a>
    <a href="ownerretrieval.php">Owner Retrieval</a>
    <a href="previouscars.php">Previous Cars</a>
    <a href="aboutus.php">About Us</a>
    <a href="login.php">Sign Out</a>
</div>

<div class="topbar">Addis Garage</div>

<div class="main">
    <div class="page-title">Previous Cars</div>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Model</th>
                <th>License Plate</th>
                <th>Code</th>
                <th>Owner Name</th>
                <th>Phone Number</th>
                <th>Issue Details</th>
                <th>Total Cost</th>
                <th>Entry Date</th>
                <th>Completion Date</th>
                <th>Notes</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["model"]); ?></td>
                <td><?php echo htmlspecialchars($row["license_plate"]); ?></td>
                <td><?php echo htmlspecialchars($row["code"]); ?></td>
                <td><?php echo htmlspecialchars($row["owner_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["phone_number"]); ?></td>
                <td><?php echo htmlspecialchars($row["issue_details"]); ?></td>
                <td><?php echo htmlspecialchars($row["total_cost"]); ?></td>
                <td><?php echo htmlspecialchars($row["entry_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["completion_date"]); ?></td>
                <td><?php echo htmlspecialchars($row["notes"]); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p style="text-align:center;">No records found in old_cars.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
