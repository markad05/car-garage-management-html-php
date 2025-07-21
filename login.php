<?php
session_start();
$host = "localhost";
$dbname = "web";
$dbuser = "root";
$dbpass = "";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    // Use prepared statement for login
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        // Verify the submitted password against the hashed password
        if (password_verify($password, $hashed_password)) {
            header("Location: homepage.php"); // Redirect to your homepage on successful login
            $_SESSION['userr'] = $row['password'];
            exit();
        } else {
            $error = "Incorrect username or password.";
        }
    } else {
        $error = "Incorrect username or password.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Addis Garage - Login</title>
    <style>
        .gateway-core {
  position: relative;
  width: 37%;
  height: 72%;
  flex-grow: 1;
  max-width: 420px;
  padding: 40px;
  background: rgba(5, 9, 20, 0.95);
  border-radius: 30px;
  border: 2px soid rgba(138, 43, 226, 0.8);
  box-shadow: 0 0 110px rgba(138, 43, 226, 0.6),
             inset 0 0 45px rgba(0, 191, 225, 0.3);
  z-index: 3;
  backdrop-filter: blur(15px);
}
@keyframes gatewayActivation {
  0% { transform: scale(0.2) rotate(90deg); opacity: 0;}
  50% { transform: scale(1.08) rotate(-5deg); opacity: 1;}
  100% { transform: scale(1) rotate(0deg); opacity: 1;}
}
h2 {
  color:#00bfff;
  text-align: center;
  font-size: 44px;
  margin-bottom: 65px;
  text-transform: uppercase;
  letter-spacing: 8px;
  text-shadow: 0 0 25px #00bfff;
  animation: voidPulse 3.2s infinite ease-in-out;
}
@keyframes voidPulse {
  0%, 100% { text-shadow: 0 0 25px #00bfff;}
  50% { text-shadow: 0 0 50px #00bfff, 0 0 70px #8a2be2;}
}
.input-portal {
  position: relative;
  margin-bottom: 35px; 
}
.input-portal input {
  width: 100%;
  padding: 14px;    /*22px;*/
  margin: 10px 0;  /* added line */
  background: rgba(138, 43, 226, 0.07);
  border: 2px solid rgba(138, 43, 226, 0.6);
  border-radius: 14px;
  color: #00bfff;
  font-size: 16px;  /* 22px;  */
  outline: none;
  transition: all 0.5s ease;
}
.input-portal input:focus{
  border-color: #8a2be2;
  box-shadow: 0 0 35px rgba(0, 191, 255, 0.8);
  transform: scale(1.03);
}
.input-portal::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 3px;
  background: rgba(138, 43, 226, 0.9);
  transition: width 0.5s ease;
}
.input-portal input:focus ~ ::after {
  width: 100%;
  animation: riftTrail 2.2s infinite linear;
}
@keyframes riftTrail {
  0% {transform: translateX(-100%);}
  100% { transform: translateX(100%);}
}
.input-portal label {
  position: absolute;
  top: -14px;
  left: 25px;
  color: #00bfff;
  font-size: 18px;
  background: rgba(5, 9, 20, 0.95);
  padding: 6px 15px;
  border-radius: 10px;
  transition: all 0.5s ease;
}
.input-portal input:focus + label {
  color: #8a2be2;
  transform: scale(1.05);
}
.launch-btn {
  width: 100%;
  padding: 14px;  /*24px;*/
  margin: 10px 0; /**/
  background: linear-gradient(45deg, #00bfff, #8a2be2);
  border: none;
  border-radius: 50px;
  color: #050914;
  font-size: 16px;  /* 26px;*/
  font-weight: bold;
  cursor: pointer;
  position: relative;
  transition: all 0.6s ease;
}
.launch-btn:hover {
  transform: scale(1.06) translateY(-12px);
  box-shadow: 0 0 60px rgba(138, 43, 226, 0.9);
}
.launch-btn::after {
  content: '';
  position: absolute;
  top: /*-70px*/ -20px;
  left: -20px;
  width: 140%;
  height: 140%;
}
.launch-btn:hover::after {
  opacity: 1;
  animation: riftBurst 3s infinite linear;
}
@keyframes riftBurst {
  0% {transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
body {
    background-image: url('blur3.jpg');
            background-size: cover;
  display : flex;
    padding-bottom: 50px; 
  justify-content: center;
  align-items: center;
  flex-direction: column;
  min-height: 100vh;
  overflow: auto;
}
      /*  body {
            background-image: url('blur3.jpg');
            background-size: cover;
            overflow: hidden;
            min-height: 100vh;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        } */
        h1 {
            font-size: 75px;
            color: rgb(24, 221, 24);
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            width: 400px;
            background-color: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        button {
            width: 120px;
            height: 50px;
            background-color: red;
            font-size: 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            background-color: darkred;
        }
        dialog {
            border: none;
            border-radius: 5px;
            padding: 20px;
            font-size: 18px;
            font-family: 'Times New Roman', Times, serif;
            color: #333;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        dialog::backdrop {
            background: rgba(0,0,0,0.3);
        }
        .signup-link {
            margin-top: 20px;
            font-size: 16px;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .reg-link {
  text-align: center;
  margin-top: 45px;
  color: #00bfff;
  font-size: 20px;
}
.reg-link a {
  color: #8a2be2;
  text-decoration: none;
  position: relative;
  transition: all 0.5s ease;
}
.reg-link a:hover {
  letter-spacing: 3px;
  text-shadow: 0 0 25px #8a2be2;
}
.reg-link a::before {
  content: '';
  position: relative;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 3px;
  background: #8a2be2;
  transition: width 0.5s ease;
}
.reg-link a:hover::before {
  width: 100%;
  animation: voidStream 1.3s infinite linear;
}
@keyframes voidStream {
  0% { transform: translateX(0);}
  50% { transform: translateX(20px);}
  100% {transform: translateX(0);}
}
    </style>
</head>
<body>
  <h3>&nbsp;</h3>
<div class="gateway-core">
        <form method="post" action="login.php">
            <h2>Login</h2>
            <?php if (!empty($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="input-portal">
               
                <input type="text" name="username" required>
                <label> Username</label>
            </div>
            <div class="input-portal">
                <label>Password</label>
                <input type="password" name="password" required>
              </div>
                
                    <button type="submit" class="launch-btn">Login</button>
            
        </form>

        <div class="reg-link">
            Don't have an account? <a href="signup.php">Sign Up here</a>
        </div>
    </div>
    <script>
        // No longer need a dialog for login errors as they are displayed directly
        // Keeping for consistency if other dialogs are added later.
        // If there was a login error, clear inputs.
        <?php if (!empty($error)) : ?>
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        <?php endif; ?>
    </script>
</body>
</html>