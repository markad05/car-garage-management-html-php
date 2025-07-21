<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = $error = "";
if(!isset($_SESSION['userr'])) { header("location: login.php");} 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $license_plate = $_POST['license_plate'] ?? '';

    $check = $conn->prepare("SELECT * FROM cars WHERE license_plate = ?");
    $check->bind_param("s", $license_plate);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
        if (strtolower($car['status']) === 'finished') {
            $completion_date = date('Y-m-d'); // current date for completion

            // Insert into old_cars with entry_date from cars table
            $insert = $conn->prepare("INSERT INTO old_cars (model, license_plate, code, owner_name, phone_number, picture, issue_details, total_cost, entry_date, completion_date, notes) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param(
                "sssssssssss",
                $car['model'],
                $car['license_plate'],
                $car['code'],
                $car['owner_name'],
                $car['phone_number'],
                $car['picture'],
                $car['issue_details'],
                $car['total_cost'],
                $car['entry_date'],      // pulls directly from original record
                $completion_date,        // new completion date
                $car['notes']
            );

            if ($insert->execute()) {
                $delete = $conn->prepare("DELETE FROM cars WHERE license_plate = ?");
                $delete->bind_param("s", $license_plate);
                if ($delete->execute()) {
                    $success = "Car collected by owner.";
                } else {
                    $error = "Error during deletion after archiving.";
                }
                $delete->close();
            } else {
                $error = "Error inserting into old_cars.";
            }
            $insert->close();
        } else {
            $error = "Car is not yet finished. Cannot be retrieved.";
        }
    } else {
        $error = "No car found with that license plate.";
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Retrieval</title>
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

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-group button {
            padding: 10px 20px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .message.success { color: green; }
        .message.error { color: red; }
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
    <div class="page-title">Owner Retrieval</div>

    <div class="form-container">
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>License Plate</label>
                <input type="text" name="license_plate" required>
            </div>

            <div class="form-group">
                <button type="submit">Check Out Car</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
