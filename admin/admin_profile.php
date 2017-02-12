<!DOCTYPE html>
 <head>
	<title>CapstoneDB</title>
	<?php include("../resources/text/link.php");?>
	<?php
		include("../php/admin_session.php");
	?>
</head>
<body>
	<div id="header">
		<h1>CapstoneDB Student Management System</h1>
	</div>
	
	
	
	
	<div id="nav">
		<?php 
			include("../resources/menu/admin_menu.php");
		?>
		
		<br><br><br><br>
	</div>
	
	
	
	<div id="section">
		<H3>Success! You are logged in as Admin</H3>

		<p>You are currently on the admin splash page.
		<br>
		Select an option from the menu to get started.</p>	
		
		<br><br><br>
		
	</div>



	<div id="footer">
		<?PHP include("../resources/text/footer.php") ?> 
	</div>
	
</body>   
</html>    
