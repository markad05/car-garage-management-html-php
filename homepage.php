<?php session_start();
if(!isset($_SESSION['userr'])) { header("location: login.php");} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; height: 100vh; overflow: hidden; }

        .sidebar {
            width: 200px;
            background: #2c3e50;
            color: white;
            padding: 20px 10px;
            
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
        
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

        .welcome-box {
            max-width: 800px;
            margin: 0 auto 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .welcome-box h2 {
            color: #16a085;
            margin-bottom: 10px;
        }

        .welcome-box p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .welcome-box a {
            display: inline-block;
            padding: 10px 20px;
            background: #1abc9c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .image-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
           
        }

        .image-row img {
            width: 250px;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
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
        <div class="page-title">Welcome</div>

        <div class="welcome-box">
            <h2>Welcome to Addis Garage</h2>
            <p>We are dedicated to providing reliable and efficient car repair and maintenance services. Track your customer vehicle repair progress in this place.</p>
            <a href="inventory.php">View Inventory</a>
        </div>

        
        <div class="image-row">
            <img src="gar1.jpg" alt="Garage 1">
            <img src="gar2.jpg" alt="Garage 2">
            <img src="gar3.jpg" alt="Garage 3">
        </div>
    </div>

</body>
</html>
