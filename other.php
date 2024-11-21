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
    <h1><?php echo htmlspecialchars($_SESSION['username']); ?></h1>
	
	<a href="dashboard.php">Back</a>
</body>
</html>
