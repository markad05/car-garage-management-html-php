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
    $code = $_POST['code'] ?? '';
    $model = $_POST['model'] ?? '';
    $owner_name = $_POST['owner_name'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $status = "Still Repair"; 
    $issue_details = $_POST['issue_details'] ?? '';
    $spare_parts_needed = $_POST['spare_parts_needed'] ?? '';
    $total_cost = $_POST['total_cost'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $picture = $_POST['picture'] ?? '';
    $entry_date = date('Y-m-d');

    // Check number of cars
    $countResult = $conn->query("SELECT COUNT(*) AS total FROM cars");
    $rowCount = $countResult->fetch_assoc()['total'];

    if ($rowCount >= 15) {
        $error = "Cannot add more than 15 cars to the inventory.";
    } else {
        // Check for duplicate license plate
        $checkStmt = $conn->prepare("SELECT * FROM cars WHERE license_plate = ?");
        $checkStmt->bind_param("s", $license_plate);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "A car with this license plate already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO cars (license_plate, code, model, owner_name, phone_number, status, issue_details, spare_parts_needed, total_cost, notes, picture, entry_date) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $license_plate, $code, $model, $owner_name, $phone_number, $status, $issue_details, $spare_parts_needed, $total_cost, $notes, $picture, $entry_date);

            if ($stmt->execute()) {
                $success = "Car added successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Car - Addis Garage</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; height: 100vh; overflow: hidden; }

        .sidebar {
            width: 200px;
            background: #2c3e50;
            color: white;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .sidebar a:hover {
            background-color: #34495e;
            transform: translateX(5px);
        }

        .sidebar a.active {
            background-color: #1abc9c;
            font-weight: bold;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar .sign-out {
            margin-top: auto;
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
        
        .form-container {
            max-width: 700px;
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-group button {
            padding: 10px 20px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .form-group button:hover {
            background: #16a085;
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
        <a href="homepage.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'homepage.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="inventory.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> Inventory
        </a>
        <a href="addcar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'addcar.php' ? 'active' : ''; ?>">
            <i class="fas fa-car"></i> Add Car
        </a>
        <a href="finishcar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'finishcar.php' ? 'active' : ''; ?>">
            <i class="fas fa-check-circle"></i> Finish Car
        </a>
        <a href="ownerretrieval.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ownerretrieval.php' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i> Owner Retrieval
        </a>
        <a href="previouscars.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'previouscars.php' ? 'active' : ''; ?>">
            <i class="fas fa-history"></i> Previous Cars
        </a>
        <a href="aboutus.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'aboutus.php' ? 'active' : ''; ?>">
            <i class="fas fa-info-circle"></i> About Us
        </a>
        <a href="login.php" class="sign-out <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>




    <div class="topbar">Addis Garage</div>

<div class="main">
    <div class="page-title">Add Car</div>

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
             <label>Code</label>
             <select name="code" required>
              <option value="">Select Code</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
             </select>
            </div>


            <div class="form-group">
                <label>Model</label>
                <input type="text" name="model" required>
            </div>

            <div class="form-group">
                <label>Owner Name</label>
                <input type="text" name="owner_name" required>
            </div>

            <div class="form-group">
    <label>Phone Number</label>
    <input type="text" name="phone_number" pattern="09[0-9]{8}" maxlength="10" required 
           title="Phone number must start with 09 and be exactly 10 digits.">
</div>


            <div class="form-group">
                <label>Issue Details</label>
                <textarea name="issue_details" required></textarea>
            </div>

            <div class="form-group">
                <label>Spare Parts Needed</label>
                <textarea name="spare_parts_needed"></textarea>
            </div>

            <div class="form-group">
                <label>Total Cost</label>
                <input type="number" name="total_cost" step="100" required>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes"></textarea>
            </div>

            <div class="form-group">
                <label>Picture URL (optional)</label>
                <input type="text" name="picture">
            </div>

            <div class="form-group">
                <button type="submit">Add Car</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>