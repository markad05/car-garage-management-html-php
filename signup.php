<?php

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
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST["new_username"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Basic validation
    if (empty($new_username) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required for sign up.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) { // Example: password length requirement
        $error = "Password must be at least 6 characters long.";
    } else {
        // Check if username already exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $new_username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Username already exists. Please choose a different one.";
        } else {
            // Hash the password before storing
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $new_username, $hashed_new_password);

            if ($stmt_insert->execute()) {
                $success_message = "Account created successfully! You can now log in.";
                // Clear the input fields on success
                unset($_POST['new_username']);
                unset($_POST['new_password']);
                unset($_POST['confirm_password']);
            } else {
                $error = "Error creating account. Please try again.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Addis Garage - Sign Up</title>
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
        h1 {
            font-size: 75px;
            color: antiquewhite;
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
        .login-link {
            margin-top: 20px;
            font-size: 16px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h3>&nbsp;</h3>

    <div class="gateway-core">
        <form method="post" action="signup.php" id="signupForm">
            <h2>Sign Up</h2>
            <?php if (!empty($error)) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php elseif (!empty($success_message)) : ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <div class="input-portal">
            <label>
                Username</label>
                <input type="text" name="new_username" id="new_username" required value="<?php echo isset($_POST['new_username']) ? htmlspecialchars($_POST['new_username']) : ''; ?>">
            </div>
            <div class="input-portal">
                <label>Password</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="input-portal">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required></div>
                <button type="submit" class="launch-btn">Sign Up</button>
            </span>
        </form>
        <div class="reg-link">
            Already have an account? <a href="login.php">Log In here</a>
        </div>
    </div>

    <script>
        // No explicit dialog needed, messages are displayed directly
        // The value attribute for username input will keep the value on error,
        // but passwords are always cleared for security.
        <?php if (!empty($error) || !empty($success_message)) : ?>
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
        <?php endif; ?>
    </script>
</body>
</html>