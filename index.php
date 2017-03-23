<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("resources/menu/login_menu.php");
			echo get_login_menu('index');
		?>
	</div>
	
	
	
	<div id="section">
		<H3>Welcome!</H3>

		<p>To begin, please select a login from the menu.</p>
	</div>


	<div id="footer">
		<?PHP include("resources/text/footer.php");
				error_reporting(E_ALL); 
				ini_set('display_errors', 1);
		?> 
	</div>
	
</body>   
</html>    
