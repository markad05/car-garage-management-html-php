<?php
session_start();
if(!isset($_SESSION['userr'])) { header("location: login.php"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Addis Garage</title>
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
            background:white;
            padding: 100px 40px 20px 40px;
        }

        .section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px gray;
        }

        .section h2 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .section p {
            line-height: 1.6;
        }

        .contact-info {
            margin-top: 10px;
        }

        .contact-info div {
            margin-bottom: 8px;
        }

        .social-buttons {
            margin-top: 20px;
        }

        .social-buttons a {
            display: inline-block;
            margin-right: 15px;
            padding: 10px 20px;
            background: lightblue;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            
        }

        .social-buttons a.youtube {
            background: red;
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
    <div class="section">
        <h2>Our Mission</h2>
        <p>At Addis Garage, our mission is to provide honest, high-quality, and affordable car repair services. We believe in building trust through transparency and commitment to excellence.</p>
    </div>

    <div class="section">
        <h2>Founding</h2>
        <p>Addis Garage was founded in 2022 by a group of passionate engineers and mechanics dedicated to solving local transportation issues by offering dependable car maintenance and repair services in Addis Ababa.</p>
    </div>

    <div class="section">
        <h2>Contact Information</h2>
        <div class="contact-info">
            <div><strong>Phone:</strong> 0953372204</div>
            <div><strong>Email:</strong> addis@gmail.com</div>
        </div>
        <div class="social-buttons">
            <a href="https://facebook.com" target="_blank">Facebook</a>
            <a href="https://youtube.com" class="youtube" target="_blank">YouTube</a>
        </div>
    </div>
</div>

</body>
</html>
