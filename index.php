<?php
	session_start();
	
	$error_message = "";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		if ($username == "admin" && $password == "password") {
			$_SESSION['username'] = $username;
			$_SESSION['logged_in'] = true;
		
			header('Location: dashboard.php');
			exit();
		} else {
			$error_message = "Wrong password or username.";
		}
	}
	
	if (isset($_SESSION['error_message'])) {
		$error_message = $_SESSION['error_message'];
		unset($_SESSION['error_message']);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style/style.css">
	</head>
	<body>
		<div class="login-div">
			<form method="POST" action="index.php">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" required>
				<label for="password">Password</label>
				<input type="password" id="password" name="password" required>
				<input type="submit" class="submit" value="Login">
			</form>
			
			<?php if (!empty($error_message)): ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
		</div>
	</body>
</html>