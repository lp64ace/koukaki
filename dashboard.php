<?php
	session_start();

	if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
		$_SESSION['error_message'] = "You need to be logged in to access this page";
		
		header('Location: index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>You are logged in.</p>
	
	<div class="dashboard">
		<a href="other.php">Redirect</a>
		<a href="logout.php">Logout</a>
	</div>
</body>
</html>
