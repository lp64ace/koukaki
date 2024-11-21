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
	  <link rel="stylesheet" type="text/css" href="style/style.css">
	  <link rel="stylesheet" type="text/css" href="style/snake.css">
	</head>
	<body>
		<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
	
		<div class="board" id="board">
			<div class="snake" id="snake2"></div>
			<div class="snake" id="snake1"></div>
			<div class="snake" id="snake0"></div>
		</div>
		
		<h1 id="score">Score: 0</h1>
		
		<a href="logout.php">Logout</a>
	</body>
	<script type="text/javascript">
		const boardx = 20, boardy = 20;
		document.getElementById('board').style.width = (boardx * 16).toString() + "px";
		document.getElementById('board').style.height = (boardy * 16).toString() + "px";
		var direction = 'r';
		var timer;
		
		function random(min, max) {
			return Math.floor(Math.random() * (max - min)) + min;
		}
		
		function redraw_food(k) {
			var food_x = random(0, boardx - 1) * 16;
			var food_y = random(0, boardx - 1) * 16;
			
			document.getElementById('food' + k).style.left = (food_x + document.getElementById('board').offsetLeft) + 'px';
			document.getElementById('food' + k).style.top = (food_y + document.getElementById('board').offsetTop) + 'px';
		}
		
		function create_food(k) {
			var incr = "<div class=\"food\" id=\"food"+k+"\"/>";
			document.getElementById('board').innerHTML += incr;
			
			redraw_food(k);
		}
		
		function touches_food(k) {
			var food_x = document.getElementById('food' + k).offsetLeft + document.getElementById('food' + k).offsetWidth / 2;
			var food_y = document.getElementById('food' + k).offsetTop + document.getElementById('food' + k).offsetHeight / 2;
			
			var head_x = document.getElementById('snake0').offsetLeft + document.getElementById('snake0').offsetWidth / 2;
			var head_y = document.getElementById('snake0').offsetTop + document.getElementById('snake0').offsetHeight / 2;
			
			return (food_x - head_x) * (food_x - head_x) + (food_y - head_y) * (food_y - head_y) < 16;
		}
		
		function touches_body_ex(k, x, y) {
			var body_x = document.getElementById('snake' + k).offsetLeft + document.getElementById('snake' + k).offsetWidth / 2;
			var body_y = document.getElementById('snake' + k).offsetTop + document.getElementById('snake' + k).offsetHeight / 2;
			
			var head_x = x + document.getElementById('snake0').offsetWidth / 2;
			var head_y = y + document.getElementById('snake0').offsetHeight / 2;
			
			return (body_x - head_x) * (body_x - head_x) + (body_y - head_y) * (body_y - head_y) < 16;
		}
		
		function touches_body(k) {
			var x = document.getElementById('snake0').offsetLeft;
			var y = document.getElementById('snake0').offsetTop;
			
			return touches_body_ex(k, x, y);
		}
		
		function redraw_snake() {
			var head_x = document.getElementById('snake0').offsetLeft;
			var head_y = document.getElementById('snake0').offsetTop;
			
			var length = 0;
			for(; document.getElementById('snake' + length); length++) {
			}
			
			document.getElementById('score').innerText = "Score: " + ((length - 3) * 100).toString();
			
			if(touches_food(0)) {
				var incr = "<div class=\"snake\" id=\"snake"+length+"\"/>";
				document.getElementById('board').innerHTML += incr;
				length++;
				
				redraw_food(0);
			}
			
			for(var k = length - 1; k >= 1; k--) {
				document.getElementById('snake' + k).style.left = document.getElementById('snake' + (k - 1)).offsetLeft + 'px';
				document.getElementById('snake' + k).style.top = document.getElementById('snake' + (k - 1)).offsetTop + 'px';
			}
			
			switch(direction) {
				case 'l': {
					document.getElementById('snake0').style.left = (head_x - 16) + 'px';
					document.getElementById('snake0').style.top = (head_y + 0) + 'px';
				} break;
				case 'r': {
					document.getElementById('snake0').style.left = (head_x + 16) + 'px';
					document.getElementById('snake0').style.top = (head_y + 0) + 'px';
				} break;
				case 'u': {
					document.getElementById('snake0').style.left = (head_x + 0) + 'px';
					document.getElementById('snake0').style.top = (head_y - 16) + 'px';
				} break;
				case 'd': {
					document.getElementById('snake0').style.left = (head_x + 0) + 'px';
					document.getElementById('snake0').style.top = (head_y + 16) + 'px';
				} break;
			}
			
			head_x = document.getElementById('snake0').offsetLeft - document.getElementById('board').offsetLeft;
			head_y = document.getElementById('snake0').offsetTop - document.getElementById('board').offsetTop;
			
			for(var k = 1; k < length; k++) {
				if(touches_body(k)) {
					clearInterval(timer);
				}
			}
			
			if(head_x < 0 || head_x >= boardx * 16) {
				clearInterval(timer);
			}
			if(head_y < 0 || head_y >= boardy * 16) {
				clearInterval(timer);
			}
		}
		
		create_food(0);
		
		for(var k = 0; document.getElementById('snake' + k); k++) {
			document.getElementById('snake' + k).style.left = document.getElementById('snake' + k).offsetLeft + document.getElementById('board').offsetLeft + 'px';
			document.getElementById('snake' + k).style.top = document.getElementById('snake' + k).offsetTop + document.getElementById('board').offsetTop + 'px';
		}
		
		function can_look(direction) {
			var x = document.getElementById('snake0').offsetLeft;
			var y = document.getElementById('snake0').offsetTop;
			
			var length = 0;
			for(; document.getElementById('snake' + length); length++) {
			}
			
			switch(direction) {
				case 'l': {
					x -= 16;
				} break;
				case 'r': {
					x += 16;
				} break;
				case 'u': {
					y -= 16;
				} break;
				case 'd': {
					y += 16;
				} break;
			}
			
			for(var k = 1; k < length; k++) {
				if(touches_body_ex(k, x, y)) {
					return false;
				}
			}
			
			return true;
		}
		
		window.addEventListener('keydown', function(event) {
			switch (event.keyCode) {
				case 37: { // left
					if(can_look('l')) {
						direction = 'l';
					}
				} break;
				case 38: { // Up
					if(can_look('u')) {
						direction = 'u';
					}
				} break;
				case 39: { // Right
					if(can_look('r')) {
						direction = 'r';
					}
				} break;
				case 40: { // Down
					if(can_look('d')) {
						direction = 'd';
					}
				} break;
			}
		}, false);
			
		timer = setInterval('redraw_snake()', 200);
	</script>
</html>
