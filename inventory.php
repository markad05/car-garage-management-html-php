<?php
session_start();
if(!isset($_SESSION['userr'])) { header("location: login.php"); }
$conn = new mysqli("localhost", "root", "", "web");
if ($conn->connect_error) die("Connection failed");

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
$cars = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    min-height: 100vh;
    overflow: hidden;
    background: #f0f9ff;
}

/*@import Google Fonts*/
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* Sidebar and Topbar (unchanged, but adjusted for consistency) */
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

.main {
    flex: 1;
    overflow-y: auto;
    background: #f0f9ff;
    padding: 100px 30px 50px;
}

/* Page Title */
.page-title {
    font-size: 36px;
    font-weight: 700;
    text-align: center;
    color: #2c3e50;
    margin-bottom: 40px;
    position: relative;
}

.page-title::after {
    content: '';
    width: 80px;
    height: 4px;
    background: #1abc9c;
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* Inventory Cards */
.inventory {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
    padding: 0 10px;
}

.car-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
    display: flex;
    flex-direction: column;
}

.car-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.car-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.3s;
}

.car-card:hover img {
    transform: scale(1.05);
}

/* Gradient Overlay for Image */
.car-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 180px;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), transparent);
    z-index: 1;
}

.car-name {
    padding: 15px;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    background: #f9f9f9;
    flex-grow: 1;
    transition: color 0.3s;
}

.car-card:hover .car-name {
    color: #1abc9c;
}

/* License Plate Hover Effect */
.license-hover {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(26, 188, 156, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s;
}

.car-card:hover .license-hover {
    opacity: 1;
}

/* Details Popup */
.car-detail-popup {
    position: fixed;
    top: 90px;
    left: 230px;
    right: 30px;
    bottom: 30px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    padding: 30px;
    overflow-y: auto;
    z-index: 20;
    display: none;
    animation: slideIn 0.3s ease-out;
    max-width: 800px;
    margin: 0 auto;
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.car-detail-popup h2 {
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    border-bottom: 2px solid #1abc9c;
    padding-bottom: 10px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    font-size: 16px;
    color: #34495e;
}

.detail-label {
    font-weight: 600;
    color: #1abc9c;
    min-width: 120px;
}

.detail-item span:not(.detail-label) {
    flex: 1;
    background: #f0f9ff;
    padding: 8px 12px;
    border-radius: 6px;
    border-left: 3px solid #1abc9c;
}

.close-btn {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    float: right;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.3s;
}

.close-btn:hover {
    background: #c0392b;
}
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
        <div class="page-title">Current Inventory</div>

        <div class="inventory">
            <?php foreach ($cars as $car): ?>
                <div class="car-card" onclick="showDetails(<?php echo htmlspecialchars(json_encode($car)); ?>)">
                    <img src="<?php echo $car['picture'] ?: 'images/default-car.jpg'; ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                    <div class="license-hover"><?php echo htmlspecialchars($car['license_plate']); ?></div>
                    <div class="car-name"><?php echo htmlspecialchars($car['model']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="car-detail-popup" id="detailBox">
        <button class="close-btn" onclick="document.getElementById('detailBox').style.display='none'">Close</button>
        <h2>Car Details</h2>
        <div id="detailContent"></div>
    </div>

    <script>
        function showDetails(car) {
            const detailBox = document.getElementById('detailBox');
            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
                <div class="detail-item"><span class="detail-label">Model:</span> ${car.model}</div>
                <div class="detail-item"><span class="detail-label">Status:</span> ${car.status}</div>
                <div class="detail-item"><span class="detail-label">License Plate:</span> ${car.license_plate}</div>
                <div class="detail-item"><span class="detail-label">Owner:</span> ${car.owner_name}</div>
                <div class="detail-item"><span class="detail-label">Phone:</span> ${car.phone_number}</div>
                <div class="detail-item"><span class="detail-label">Issue:</span> ${car.issue_details || 'N/A'}</div>
                <div class="detail-item"><span class="detail-label">Parts Needed:</span> ${car.spare_parts_needed || 'N/A'}</div>
                <div class="detail-item"><span class="detail-label">Total Cost:</span> ${car.total_cost || '0'}</div>
                <div class="detail-item"><span class="detail-label">Entry Date:</span> ${car.entry_date || 'N/A'}</div>
            `;
            detailBox.style.display = 'block';
        }
    </script>

</body>
</html>
<?php $conn->close(); ?>
