<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include_once("../resources/text/link.php"); error_reporting(E_ALL); 
				ini_set('display_errors', 1);?>
	<?php
		include_once("../php/teach_session.php");
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include_once("../resources/menu/teach_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<H3>Success! You are logged in as <?php echo $_SESSION['user_name'];?></H3>

		<p>You are currently on the teacher splash page.
		<br>
		Select an option from the menu to get started.</p>	
		
		<br><br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
